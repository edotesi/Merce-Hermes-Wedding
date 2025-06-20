<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'name',
        'category',
        'path',
        'thumbnail_path',
        'width',
        'height',
        'filesize',
        'order'
    ];

    /**
     * Scope para filtrar por categoría
     */
    public function scopeByCategory($query, $category)
    {
        if ($category !== 'todo') {
            return $query->where('category', $category);
        }
        return $query;
    }

    /**
     * Obtener todas las categorías disponibles
     */
    public static function getCategories()
    {
        return self::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->toArray();
    }

    /**
     * Obtener la URL completa de la imagen original
     */
    public function getImageUrlAttribute()
    {
        return asset($this->path);
    }

    /**
     * Obtener la URL completa del thumbnail
     */
    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail_path ? asset($this->thumbnail_path) : $this->image_url;
    }

    /**
     * Verificar si tiene thumbnail
     */
    public function hasThumbnail()
    {
        return !empty($this->thumbnail_path) && file_exists(public_path($this->thumbnail_path));
    }

    /**
     * Obtener el tamaño formateado del archivo
     */
    public function getFormattedFilesizeAttribute()
    {
        if (!$this->filesize) return 'N/A';

        $bytes = $this->filesize;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Obtener las dimensiones como string
     */
    public function getDimensionsAttribute()
    {
        return $this->width && $this->height ? $this->width . 'x' . $this->height : 'N/A';
    }
}
