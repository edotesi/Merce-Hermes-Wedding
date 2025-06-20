<?php

if (!function_exists('routeWithPreview')) {
    /**
     * Generar URL de ruta con parámetro de preview si está en modo mantenimiento
     */
    function routeWithPreview($routeName, $parameters = [])
    {
        $url = route($routeName, $parameters);

        // Si estamos en modo preview, añadir el token
        if (request()->query('preview') === env('MAINTENANCE_TOKEN', '')) {
            $separator = strpos($url, '?') !== false ? '&' : '?';
            $url .= $separator . 'preview=' . env('MAINTENANCE_TOKEN', '');
        }

        return $url;
    }
}

if (!function_exists('formatFileSize')) {
    /**
     * Formatear tamaño de archivo en bytes a formato legible
     */
    function formatFileSize($bytes, $precision = 2)
    {
        if ($bytes === 0 || $bytes === null) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}

if (!function_exists('generateUniqueFilename')) {
    /**
     * Generar nombre de archivo único
     */
    function generateUniqueFilename($originalName, $directory = '')
    {
        $pathInfo = pathinfo($originalName);
        $filename = $pathInfo['filename'];
        $extension = isset($pathInfo['extension']) ? '.' . $pathInfo['extension'] : '';

        $counter = 1;
        $newName = $originalName;

        while (file_exists($directory . '/' . $newName)) {
            $newName = $filename . '_' . $counter . $extension;
            $counter++;
        }

        return $newName;
    }
}

if (!function_exists('isImageFile')) {
    /**
     * Verificar si un archivo es una imagen válida
     */
    function isImageFile($filename)
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        return in_array($extension, $allowedExtensions);
    }
}

if (!function_exists('optimizeImagePath')) {
    /**
     * Optimizar ruta de imagen para web (convertir espacios, caracteres especiales)
     */
    function optimizeImagePath($path)
    {
        // Reemplazar espacios y caracteres especiales
        $path = preg_replace('/[^a-zA-Z0-9\/\-_\.]/', '_', $path);

        // Eliminar múltiples underscores consecutivos
        $path = preg_replace('/_+/', '_', $path);

        // Eliminar underscores al inicio y final de nombres de archivo
        $pathParts = explode('/', $path);
        $filename = array_pop($pathParts);
        $filename = trim($filename, '_');
        $pathParts[] = $filename;

        return implode('/', $pathParts);
    }
}

// Nuevas funciones para la galería

if (!function_exists('getCategoryIcon')) {
    /**
     * Obtener icono FontAwesome para una categoría de galería
     */
    function getCategoryIcon($category)
    {
        $icons = [
            'ceremonia' => 'church',
            'bienvenida' => 'glass-cheers',
            'banquete' => 'utensils',
            'fiesta' => 'music',
            'fotomaton' => 'camera',
            'preboda' => 'heart',
            'todo' => 'images'
        ];

        return $icons[strtolower($category)] ?? 'image';
    }
}

if (!function_exists('getCategoryDisplayName')) {
    /**
     * Obtener nombre de display para una categoría
     */
    function getCategoryDisplayName($category)
    {
        $names = [
            'ceremonia' => 'Ceremonia',
            'bienvenida' => 'Bienvenida',
            'banquete' => 'Banquete',
            'fiesta' => 'Fiesta',
            'fotomaton' => 'Fotomatón',
            'preboda' => 'Preboda',
            'todo' => 'Todas'
        ];

        return $names[strtolower($category)] ?? ucfirst($category);
    }
}

if (!function_exists('getCategoryColor')) {
    /**
     * Obtener color temático para una categoría
     */
    function getCategoryColor($category)
    {
        $colors = [
            'ceremonia' => '#d4af37',      // Dorado
            'bienvenida' => '#8fbc8f',     // Verde suave
            'banquete' => '#cd853f',       // Marrón dorado
            'fiesta' => '#ff6b6b',         // Coral
            'fotomaton' => '#4ecdc4',      // Turquesa
            'preboda' => '#ff8a95',        // Rosa suave
            'todo' => '#a79f7d'            // Color principal
        ];

        return $colors[strtolower($category)] ?? '#a79f7d';
    }
}

if (!function_exists('formatImageDimensions')) {
    /**
     * Formatear dimensiones de imagen
     */
    function formatImageDimensions($width, $height)
    {
        if (!$width || !$height) {
            return 'N/A';
        }

        return $width . 'x' . $height;
    }
}
