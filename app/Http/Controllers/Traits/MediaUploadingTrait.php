<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;

trait MediaUploadingTrait
{
    protected array $allowedMimeTypes = [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
        'application/pdf',
        'video/mp4', 'video/mpeg',
    ];

    protected int $defaultMaxSizeMb = 10;

    public function storeMedia(Request $request)
    {
        $rules = ['file' => ['required', 'file']];

        $maxKb = $request->has('size')
            ? (int) $request->input('size') * 1024
            : $this->defaultMaxSizeMb * 1024;

        $rules['file'][] = 'max:' . $maxKb;

        if ($request->has('width') || $request->has('height')) {
            $rules['file'][] = 'image';
            $rules['file'][] = sprintf(
                'dimensions:max_width=%s,max_height=%s',
                $request->input('width', 100000),
                $request->input('height', 100000)
            );
        }

        $this->validate($request, $rules);

        $file = $request->file('file');

        if (!in_array($file->getMimeType(), $this->allowedMimeTypes, true)) {
            return response()->json(['error' => 'نوع الملف غير مسموح به.'], 422);
        }

        $path = storage_path('tmp/uploads');

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $originalName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($file->getClientOriginalName()));
        $name = bin2hex(random_bytes(8)) . '_' . $originalName;

        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }
}
