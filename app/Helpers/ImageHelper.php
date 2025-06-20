<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Tamaños de thumbnails disponibles
     */
    const THUMBNAIL_SIZES = [
        'small' => 150,   // Para grid de thumbnails
        'medium' => 300,  // Para carrusel
        'large' => 600    // Para preview
    ];

    /**
     * Calidad JPEG por defecto
     */
    const JPEG_QUALITY = 85;

    /**
     * Generar thumbnails para una imagen
     */
    public static function generateThumbnails($originalPath, $category, $filename)
    {
        $fullPath = public_path($originalPath);

        if (!file_exists($fullPath)) {
            throw new \Exception("Archivo no encontrado: {$fullPath}");
        }

        // Obtener información de la imagen
        $imageInfo = getimagesize($fullPath);
        if ($imageInfo === false) {
            throw new \Exception("No se puede leer la imagen: {$fullPath}");
        }

        [$originalWidth, $originalHeight, $imageType] = $imageInfo;

        // Crear imagen desde archivo
        $sourceImage = self::createImageFromFile($fullPath, $imageType);
        if ($sourceImage === false) {
            throw new \Exception("No se puede crear la imagen desde el archivo");
        }

        $thumbnails = [];
        $baseDir = "images/gallery/thumbnails/{$category}";
        $thumbnailDir = public_path($baseDir);

        // Crear directorio si no existe
        if (!file_exists($thumbnailDir)) {
            mkdir($thumbnailDir, 0755, true);
        }

        // Generar cada tamaño de thumbnail
        foreach (self::THUMBNAIL_SIZES as $sizeName => $maxSize) {
            $thumbnailPath = self::generateSingleThumbnail(
                $sourceImage,
                $originalWidth,
                $originalHeight,
                $imageType,
                $maxSize,
                $baseDir,
                $filename,
                $sizeName
            );

            if ($thumbnailPath) {
                $thumbnails[$sizeName] = $thumbnailPath;
            }
        }

        // Liberar memoria
        imagedestroy($sourceImage);

        return $thumbnails;
    }

    /**
     * Crear imagen desde archivo según el tipo
     */
    private static function createImageFromFile($filePath, $imageType)
    {
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                return imagecreatefromjpeg($filePath);
            case IMAGETYPE_PNG:
                return imagecreatefrompng($filePath);
            case IMAGETYPE_GIF:
                return imagecreatefromgif($filePath);
            case IMAGETYPE_WEBP:
                return imagecreatefromwebp($filePath);
            default:
                return false;
        }
    }

    /**
     * Generar un thumbnail específico
     */
    private static function generateSingleThumbnail($sourceImage, $originalWidth, $originalHeight, $imageType, $maxSize, $baseDir, $filename, $sizeName)
    {
        // Calcular nuevas dimensiones manteniendo proporción
        $ratio = min($maxSize / $originalWidth, $maxSize / $originalHeight);
        $newWidth = round($originalWidth * $ratio);
        $newHeight = round($originalHeight * $ratio);

        // Crear nueva imagen
        $thumbnailImage = imagecreatetruecolor($newWidth, $newHeight);

        // Configurar transparencia para PNG
        if ($imageType === IMAGETYPE_PNG) {
            imagealphablending($thumbnailImage, false);
            imagesavealpha($thumbnailImage, true);
            $transparent = imagecolorallocatealpha($thumbnailImage, 255, 255, 255, 127);
            imagefill($thumbnailImage, 0, 0, $transparent);
        }

        // Redimensionar imagen
        imagecopyresampled(
            $thumbnailImage, $sourceImage,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $originalWidth, $originalHeight
        );

        // Generar nombre del archivo
        $pathInfo = pathinfo($filename);
        $thumbnailFilename = $pathInfo['filename'] . "_{$sizeName}." . $pathInfo['extension'];
        $thumbnailPath = "{$baseDir}/{$thumbnailFilename}";
        $fullThumbnailPath = public_path($thumbnailPath);

        // Guardar thumbnail
        $saved = false;
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $saved = imagejpeg($thumbnailImage, $fullThumbnailPath, self::JPEG_QUALITY);
                break;
            case IMAGETYPE_PNG:
                $saved = imagepng($thumbnailImage, $fullThumbnailPath, 6);
                break;
            case IMAGETYPE_GIF:
                $saved = imagegif($thumbnailImage, $fullThumbnailPath);
                break;
            case IMAGETYPE_WEBP:
                $saved = imagewebp($thumbnailImage, $fullThumbnailPath, self::JPEG_QUALITY);
                break;
        }

        // Liberar memoria
        imagedestroy($thumbnailImage);

        return $saved ? $thumbnailPath : null;
    }

    /**
     * Obtener información completa de una imagen
     */
    public static function getImageInfo($imagePath)
    {
        $fullPath = public_path($imagePath);

        if (!file_exists($fullPath)) {
            return null;
        }

        $imageInfo = getimagesize($fullPath);
        if ($imageInfo === false) {
            return null;
        }

        return [
            'width' => $imageInfo[0],
            'height' => $imageInfo[1],
            'type' => $imageInfo[2],
            'mime' => $imageInfo['mime'],
            'filesize' => filesize($fullPath)
        ];
    }

    /**
     * Escanear directorio y subdirectorios para obtener todas las imágenes
     */
    public static function scanImagesInDirectory($baseDirectory)
    {
        $fullPath = public_path($baseDirectory);

        if (!is_dir($fullPath)) {
            return [];
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
        $images = [];

        // Escanear subdirectorios (categorías)
        $categories = scandir($fullPath);
        foreach ($categories as $category) {
            if ($category === '.' || $category === '..') continue;

            $categoryPath = $fullPath . '/' . $category;

            // Si es un directorio, escanear las imágenes dentro
            if (is_dir($categoryPath)) {
                $files = scandir($categoryPath);
                foreach ($files as $file) {
                    if ($file === '.' || $file === '..') continue;

                    $filePath = $categoryPath . '/' . $file;
                    if (!is_file($filePath)) continue;

                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    if (in_array($extension, $allowedExtensions)) {
                        $images[] = [
                            'name' => $file,
                            'category' => strtolower($category),
                            'path' => $baseDirectory . '/' . $category . '/' . $file,
                            'fullPath' => $filePath,
                            'relativePath' => $category . '/' . $file
                        ];
                    }
                }
            }
            // También permitir archivos directamente en el directorio base (por compatibilidad)
            else if (is_file($categoryPath)) {
                $extension = strtolower(pathinfo($category, PATHINFO_EXTENSION));
                if (in_array($extension, $allowedExtensions)) {
                    $images[] = [
                        'name' => $category,
                        'category' => self::extractCategoryFromFilename($category),
                        'path' => $baseDirectory . '/' . $category,
                        'fullPath' => $categoryPath,
                        'relativePath' => $category
                    ];
                }
            }
        }

        return $images;
    }

    /**
     * Obtener todas las categorías disponibles escaneando subdirectorios
     */
    public static function getAvailableCategories($baseDirectory = 'images/gallery/wedding')
    {
        $fullPath = public_path($baseDirectory);

        if (!is_dir($fullPath)) {
            return [];
        }

        $categories = [];
        $items = scandir($fullPath);

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;

            $itemPath = $fullPath . '/' . $item;
            if (is_dir($itemPath)) {
                $categories[] = strtolower($item);
            }
        }

        sort($categories);
        return $categories;
    }

    /**
     * Extraer categoría del nombre del archivo (para compatibilidad con archivos sueltos)
     */
    public static function extractCategoryFromFilename($filename)
    {
        // Buscar patrón: categoria_numero.extension
        if (preg_match('/^([a-zA-Z]+)_\d+\.[a-zA-Z]+$/', $filename, $matches)) {
            return strtolower($matches[1]);
        }

        // Si no coincide con el patrón, usar "general"
        return 'general';
    }

    /**
     * Crear estructura de directorios para categorías
     */
    public static function createCategoryDirectories($baseDirectory = 'images/gallery/wedding')
    {
        $categories = ['ceremonia', 'bienvenida', 'fotomaton', 'banquete', 'fiesta', 'preboda'];
        $created = [];

        foreach ($categories as $category) {
            $categoryPath = public_path($baseDirectory . '/' . $category);
            if (!file_exists($categoryPath)) {
                if (mkdir($categoryPath, 0755, true)) {
                    $created[] = $category;
                }
            }
        }

        return $created;
    }

    /**
     * Limpiar thumbnails huérfanos (sin imagen original)
     */
    public static function cleanOrphanThumbnails()
    {
        $thumbnailsDir = public_path('images/gallery/thumbnails');

        if (!is_dir($thumbnailsDir)) {
            return 0;
        }

        $deletedCount = 0;
        $categories = scandir($thumbnailsDir);

        foreach ($categories as $category) {
            if ($category === '.' || $category === '..') continue;

            $categoryDir = $thumbnailsDir . '/' . $category;
            if (!is_dir($categoryDir)) continue;

            $thumbnails = scandir($categoryDir);
            foreach ($thumbnails as $thumbnail) {
                if ($thumbnail === '.' || $thumbnail === '..') continue;

                // Buscar la imagen original correspondiente
                $originalName = preg_replace('/_(?:small|medium|large)\./', '.', $thumbnail);
                $originalPath = public_path("images/gallery/wedding/{$category}/{$originalName}");

                if (!file_exists($originalPath)) {
                    $thumbnailPath = $categoryDir . '/' . $thumbnail;
                    if (unlink($thumbnailPath)) {
                        $deletedCount++;
                    }
                }
            }
        }

        return $deletedCount;
    }
}
