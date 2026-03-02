<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MediaController extends Controller
{
    // ── Supported MIME types ─────────────────────────────────
    private const ALLOWED_MIMES = [
        'image/jpeg', 'image/jpg', 'image/png', 'image/gif',
        'image/webp', 'image/svg+xml',
    ];

    private const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 MB

    // Thumbnail width in pixels — height scales proportionally
    private const THUMB_WIDTH = 400;

    // ── List ────────────────────────────────────────────────────
    public function index(Request $request): JsonResponse
    {
        $query = Media::with('uploader:id,name')
            ->inCollection($request->input('collection', 'blog'))
            ->orderByDesc('created_at');

        if ($search = $request->input('search')) {
            $query->search($search);
        }

        $media = $query->paginate(30);

        return response()->json($media);
    }

    // ── Upload ──────────────────────────────────────────────────
    public function store(Request $request): JsonResponse
    {
        // Validate
        $request->validate([
            'file'       => [
                'required',
                'file',
                'max:5120',                   // 5 MB in KB
                'mimes:jpg,jpeg,png,gif,webp,svg',
            ],
            'alt_text'   => 'nullable|string|max:250',
            'collection' => 'nullable|string|max:80|alpha_dash',
        ]);

        $file       = $request->file('file');
        $collection = $request->input('collection', 'blog');
        $admin      = $request->user('admin');

        // Build storage path: blog/2026/03/
        $folder   = "{$collection}/" . now()->format('Y/m');
        $uuid     = Str::uuid()->toString();
        $ext      = strtolower($file->getClientOriginalExtension());
        $safeName = $uuid . '.' . $ext;
        $path     = "{$folder}/{$safeName}";

        // Store original file
        $file->storeAs($folder, $safeName, 'public');

        // Get image dimensions and generate thumbnail
        $width  = null;
        $height = null;
        $thumbPath = null;
        $thumbUrl  = null;
        $mimeType  = $file->getMimeType();

        if ($mimeType !== 'image/svg+xml') {
            try {
                [$width, $height] = @getimagesize($file->getRealPath()) ?: [null, null];

                // Generate WebP thumbnail using GD (no extra install needed)
                if ($width && $height && $width > self::THUMB_WIDTH) {
                    $thumbName   = $uuid . '_thumb.webp';
                    $thumbFolder = "{$folder}";
                    $thumbPath   = "{$thumbFolder}/{$thumbName}";

                    $this->generateThumbnail(
                        $file->getRealPath(),
                        storage_path("app/public/{$thumbPath}"),
                        self::THUMB_WIDTH,
                        $mimeType,
                    );

                    $thumbUrl = Storage::disk('public')->url($thumbPath);
                }
            } catch (\Throwable $e) {
                // Non-fatal — continue without thumbnail
                report($e);
            }
        }

        $publicUrl = Storage::disk('public')->url($path);

        $altText = $request->input('alt_text')
            ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $media = Media::create([
            'uploaded_by'    => $admin->id,
            'filename'       => $file->getClientOriginalName(),
            'disk'           => 'public',
            'path'           => $path,
            'url'            => $publicUrl,
            'mime_type'      => $mimeType,
            'file_size'      => $file->getSize(),
            'width'          => $width,
            'height'         => $height,
            'alt_text'       => $altText,
            'thumbnail_path' => $thumbPath,
            'thumbnail_url'  => $thumbUrl,
            'collection'     => $collection,
            'search_text'    => implode(' ', array_filter([
                $file->getClientOriginalName(),
                $altText,
                $collection,
            ])),
        ]);

        return response()->json([
            'success' => true,
            'media'   => $media,
            'message' => 'Image uploaded successfully.',
        ], 201);
    }

    // ── Update alt text / caption ───────────────────────────────
    public function update(Request $request, Media $media): JsonResponse
    {
        $data = $request->validate([
            'alt_text' => 'nullable|string|max:250',
            'caption'  => 'nullable|string|max:500',
            'title'    => 'nullable|string|max:250',
        ]);

        $media->update($data);

        // Refresh search_text
        $media->update([
            'search_text' => implode(' ', array_filter([
                $media->filename,
                $media->alt_text,
                $media->caption,
                $media->collection,
            ])),
        ]);

        return response()->json(['success' => true, 'media' => $media]);
    }

    // ── Delete ──────────────────────────────────────────────────
    public function destroy(Media $media): JsonResponse
    {
        // Delete files from disk
        Storage::disk('public')->delete($media->path);
        if ($media->thumbnail_path) {
            Storage::disk('public')->delete($media->thumbnail_path);
        }

        $media->delete(); // soft delete

        return response()->json(['success' => true, 'message' => 'Image deleted.']);
    }

    // ── GD-based WebP thumbnail generator (no Imagick needed) ──
    private function generateThumbnail(
        string $sourcePath,
        string $destPath,
        int    $maxWidth,
        string $mimeType,
    ): void {
        // Create source image resource
        $src = match ($mimeType) {
            'image/jpeg', 'image/jpg' => imagecreatefromjpeg($sourcePath),
            'image/png'               => imagecreatefrompng($sourcePath),
            'image/gif'               => imagecreatefromgif($sourcePath),
            'image/webp'              => imagecreatefromwebp($sourcePath),
            default => throw new \RuntimeException("Unsupported type: {$mimeType}"),
        };

        if (! $src) {
            throw new \RuntimeException("Failed to read image: {$sourcePath}");
        }

        $origW = imagesx($src);
        $origH = imagesy($src);
        $scale = min(1, $maxWidth / $origW);
        $newW  = (int) round($origW * $scale);
        $newH  = (int) round($origH * $scale);

        $thumb = imagecreatetruecolor($newW, $newH);

        // Preserve transparency for PNG/WebP
        if (in_array($mimeType, ['image/png', 'image/webp'], true)) {
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
            $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
            imagefilledrectangle($thumb, 0, 0, $newW, $newH, $transparent);
        }

        imagecopyresampled($thumb, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

        // Ensure destination directory exists
        $destDir = dirname($destPath);
        if (! is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        // Save as WebP (quality 80)
        imagewebp($thumb, $destPath, 80);

        imagedestroy($src);
        imagedestroy($thumb);
    }
}