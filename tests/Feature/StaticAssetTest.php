<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StaticAssetTest extends TestCase
{
    /**
     * Test endpoint /ppdb/assets/ melayani file publik dari public/assets/.
     */
    public function test_serves_assets_successfully(): void
    {
        $response = $this->get('/ppdb/assets/logo-penus.png');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
        $this->assertStringContainsString('max-age=86400', $response->headers->get('Cache-Control') ?? '');
    }

    /**
     * Test endpoint /ppdb/build/ melayani manifest dan file build Vite dengan cache immutable.
     */
    public function test_serves_build_manifest_and_hashed_assets(): void
    {
        $manifestResponse = $this->get('/ppdb/build/manifest.json');
        $manifestResponse->assertStatus(200);
        $manifestResponse->assertHeader('Content-Type', 'application/json');

        $buildFiles = glob(public_path('build/assets/*.css'));
        if (! empty($buildFiles)) {
            $hashedFilename = basename($buildFiles[0]);
            $assetResponse = $this->get('/ppdb/build/assets/'.$hashedFilename);

            $assetResponse->assertStatus(200);
            $this->assertStringContainsString('immutable', $assetResponse->headers->get('Cache-Control') ?? '');
            $this->assertStringContainsString('max-age=31536000', $assetResponse->headers->get('Cache-Control') ?? '');
        }
    }

    /**
     * Test endpoint /ppdb/storage/ melayani file dari storage/app/public/.
     */
    public function test_serves_storage_file_successfully(): void
    {
        Storage::disk('public')->put('testing/test-file.txt', 'Static asset content verification');

        try {
            $response = $this->get('/ppdb/storage/testing/test-file.txt');

            $response->assertStatus(200);
            $this->assertSame('Static asset content verification', $response->streamedContent());
        } finally {
            Storage::disk('public')->delete('testing/test-file.txt');
        }
    }

    /**
     * Test endpoint /ppdb/images/ fallback ke public/assets/ jika public/images/ tidak memiliki file tersebut.
     */
    public function test_serves_images_with_fallback_to_assets(): void
    {
        $response = $this->get('/ppdb/images/logo-penus.png');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/png');
    }

    /**
     * Test endpoint /ppdb/uploads/ fallback ke storage/app/public/.
     */
    public function test_serves_uploads_with_fallback_to_storage(): void
    {
        Storage::disk('public')->put('uploads-test/sample.pdf', '%PDF-1.4 dummy pdf content');

        try {
            $response = $this->get('/ppdb/uploads/uploads-test/sample.pdf');

            $response->assertStatus(200);
            $response->assertHeader('Content-Type', 'application/pdf');
        } finally {
            Storage::disk('public')->delete('uploads-test/sample.pdf');
        }
    }

    /**
     * Test response 404 untuk file yang tidak ada.
     */
    public function test_returns_404_for_nonexistent_asset(): void
    {
        $response = $this->get('/ppdb/assets/file-that-does-not-exist.png');

        $response->assertStatus(404);
    }

    /**
     * Test penolakan upaya path traversal (security protection).
     */
    public function test_blocks_path_traversal_attempts(): void
    {
        $response = $this->get('/ppdb/assets/../../.env');

        $response->assertStatus(404);
    }
}
