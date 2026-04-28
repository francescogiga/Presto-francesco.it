<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    protected $fillable = ['path', 'article_id', 'labels', 'adult', 'spoof', 'racy', 'medical', 'violence'];

    protected function casts(): array
    {
        return [
            'labels' => 'array',
        ];
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public static function getUrlByFilePath(string $filePath, ?int $w = null, ?int $h = null): string
    {
        if ($w === null && $h === null) {
            return Storage::url($filePath);
        }

        $dir = dirname($filePath);
        $filename = basename($filePath);
        $cropFile = $dir . '/crop_' . $w . 'x' . $h . '_' . $filename;

        if (Storage::disk('public')->exists($cropFile)) {
            return Storage::url($cropFile);
        }

        return Storage::url($filePath);
    }

    public function getUrl(?int $w = null, ?int $h = null): string
    {
        return static::getUrlByFilePath($this->path, $w, $h);
    }
}
