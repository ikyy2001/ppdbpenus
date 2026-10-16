<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StaticAssetController extends Controller
{
    /**
     * MIME type mappings untuk memastikan header Content-Type tepat dan aman.
     *
     * @var array<string, string>
     */
    protected array $mimeTypes = [
        'css' => 'text/css; charset=UTF-8',
        'js' => 'application/javascript; charset=UTF-8',
        'json' => 'application/json',
        'svg' => 'image/svg+xml',
        'webp' => 'image/webp',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'ico' => 'image/x-icon',
        'pdf' => 'application/pdf',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'otf' => 'font/otf',
        'eot' => 'application/vnd.ms-fontobject',
        'xml' => 'application/xml',
        'txt' => 'text/plain; charset=UTF-8',
    ];

    /**
     * Sajikan file dari direktori public/assets/
     */
    public function serveAssets(Request $request, string $path): BinaryFileResponse
    {
        return $this->deliverFile(public_path('assets'), $path, false);
    }

    /**
     * Sajikan file build Vite dari direktori public/build/
     */
    public function serveBuild(Request $request, string $path): BinaryFileResponse
    {
        $isHashed = str_contains($path, 'assets/');

        return $this->deliverFile(public_path('build'), $path, $isHashed);
    }

    /**
     * Sajikan file dari storage (storage/app/public/ atau fallback public/storage/)
     */
    public function serveStorage(Request $request, string $path): BinaryFileResponse
    {
        $primaryDir = storage_path('app/public');
        $fallbackDir = public_path('storage');

        if (file_exists($primaryDir.DIRECTORY_SEPARATOR.ltrim($path, '/\\'))) {
            return $this->deliverFile($primaryDir, $path, false);
        }

        if (file_exists($fallbackDir.DIRECTORY_SEPARATOR.ltrim($path, '/\\'))) {
            return $this->deliverFile($fallbackDir, $path, false);
        }

        abort(404, 'File not found in storage');
    }

    /**
     * Sajikan file gambar dari public/images/ dengan fallback ke public/assets/
     */
    public function serveImages(Request $request, string $path): BinaryFileResponse
    {
        $primaryDir = public_path('images');
        $fallbackDir = public_path('assets');

        if (file_exists($primaryDir.DIRECTORY_SEPARATOR.ltrim($path, '/\\'))) {
            return $this->deliverFile($primaryDir, $path, false);
        }

        if (file_exists($fallbackDir.DIRECTORY_SEPARATOR.ltrim($path, '/\\'))) {
            return $this->deliverFile($fallbackDir, $path, false);
        }

        abort(404, 'Image asset not found');
    }

    /**
     * Sajikan file unggahan dari public/uploads/ dengan fallback ke storage/app/public/
     */
    public function serveUploads(Request $request, string $path): BinaryFileResponse
    {
        $primaryDir = public_path('uploads');
        $fallbackDir = storage_path('app/public');

        if (file_exists($primaryDir.DIRECTORY_SEPARATOR.ltrim($path, '/\\'))) {
            return $this->deliverFile($primaryDir, $path, false);
        }

        if (file_exists($fallbackDir.DIRECTORY_SEPARATOR.ltrim($path, '/\\'))) {
            return $this->deliverFile($fallbackDir, $path, false);
        }

        abort(404, 'Uploaded file not found');
    }

    /**
     * Validasi keamanan path traversal, boundary directory, dan kirimkan binary response.
     */
    protected function deliverFile(string $baseDir, string $relativePath, bool $immutable = false): BinaryFileResponse
    {
        if (str_contains($relativePath, '..') || str_contains($relativePath, "\0")) {
            abort(404, 'Invalid asset path');
        }

        $baseRealPath = realpath($baseDir);
        if ($baseRealPath === false) {
            abort(404, 'Base directory does not exist');
        }

        $targetPath = $baseDir.DIRECTORY_SEPARATOR.ltrim($relativePath, '/\\');
        $fileRealPath = realpath($targetPath);

        if ($fileRealPath === false || ! is_file($fileRealPath)) {
            abort(404, 'Asset not found');
        }

        $normalizedBase = rtrim(str_replace('\\', '/', strtolower($baseRealPath)), '/').'/';
        $normalizedFile = str_replace('\\', '/', strtolower($fileRealPath));

        if (! str_starts_with($normalizedFile, $normalizedBase)) {
            abort(404, 'Unauthorized asset path');
        }

        $cacheControl = $immutable
            ? 'public, max-age=31536000, immutable'
            : 'public, max-age=86400, must-revalidate';

        $extension = strtolower(pathinfo($fileRealPath, PATHINFO_EXTENSION));
        $headers = [
            'Cache-Control' => $cacheControl,
        ];

        if (isset($this->mimeTypes[$extension])) {
            $headers['Content-Type'] = $this->mimeTypes[$extension];
        }

        return response()->file($fileRealPath, $headers);
    }
}
