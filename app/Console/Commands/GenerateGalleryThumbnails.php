<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gallery;
use App\Helpers\ImageHelper;

class GenerateGalleryThumbnails extends Command
{
    protected $signature = 'gallery:generate-thumbnails
                           {--force : Regenerar thumbnails existentes}
                           {--clean : Limpiar thumbnails huérfanos}
                           {--category= : Procesar solo una categoría específica}
                           {--list-categories : Mostrar categorías disponibles}
                           {--create-dirs : Crear estructura de directorios}';

    protected $description = 'Generar thumbnails para la galería y sincronizar con la base de datos';

    public function handle()
    {
        $this->info('🖼️  Iniciando generación de thumbnails...');

        // Crear estructura de directorios si se solicita
        if ($this->option('create-dirs')) {
            $this->createDirectoryStructure();
            return;
        }

        // Mostrar categorías disponibles si se solicita
        if ($this->option('list-categories')) {
            $this->listAvailableCategories();
            return;
        }

        // Limpiar thumbnails huérfanos si se solicita
        if ($this->option('clean')) {
            $this->cleanOrphanThumbnails();
        }

        // Escanear directorio de imágenes
        $images = ImageHelper::scanImagesInDirectory('images/gallery/wedding');

        if (empty($images)) {
            $this->warn('No se encontraron imágenes en el directorio images/gallery/wedding/');
            $this->info('💡 Opciones disponibles:');
            $this->info('   php artisan gallery:generate-thumbnails --create-dirs  # Crear estructura');
            $this->info('   php artisan gallery:generate-thumbnails --list-categories  # Ver categorías');
            return;
        }

        $this->info("📁 Encontradas " . count($images) . " imágenes");

        // Filtrar por categoría si se especifica
        $targetCategory = $this->option('category');
        if ($targetCategory) {
            $images = array_filter($images, function($image) use ($targetCategory) {
                return $image['category'] === $targetCategory;
            });
            $this->info("🎯 Filtrado por categoría '{$targetCategory}': " . count($images) . " imágenes");
        }

        $force = $this->option('force');
        $processed = 0;
        $created = 0;
        $updated = 0;
        $errors = 0;

        // Crear barra de progreso
        $progressBar = $this->output->createProgressBar(count($images));
        $progressBar->start();

        foreach ($images as $imageData) {
            try {
                $result = $this->processImage($imageData, $force);

                if ($result['created']) {
                    $created++;
                } elseif ($result['updated']) {
                    $updated++;
                }

                $processed++;

            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->error("❌ Error procesando {$imageData['name']}: " . $e->getMessage());
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Mostrar resumen
        $this->showSummary($processed, $created, $updated, $errors);
    }

    private function createDirectoryStructure()
    {
        $this->info('🏗️  Creando estructura de directorios...');

        $created = ImageHelper::createCategoryDirectories();

        if (!empty($created)) {
            $this->info('✅ Directorios creados:');
            foreach ($created as $category) {
                $this->info("   📁 images/gallery/wedding/{$category}/");
            }
        } else {
            $this->info('ℹ️  Todos los directorios ya existen');
        }

        $this->newLine();
        $this->info('📂 Estructura completa:');
        $this->info('public/images/gallery/wedding/');
        $this->info('├── ceremonia/     # Fotos de la ceremonia religiosa/civil');
        $this->info('├── bienvenida/    # Cocktail de bienvenida');
        $this->info('├── banquete/      # Cena, brindis y momentos de mesa');
        $this->info('├── fiesta/        # Baile y diversión nocturna');
        $this->info('├── fotomaton/     # Fotos del fotomatón');
        $this->info('└── preboda/       # Sesión de preboda');

        $this->newLine();
        $this->info('🎯 Próximos pasos:');
        $this->info('1. Sube tus fotos en los directorios correspondientes');
        $this->info('2. Ejecuta: php artisan gallery:generate-thumbnails');
    }

    private function listAvailableCategories()
    {
        $this->info('📂 Categorías disponibles:');

        // Categorías desde directorios
        $dirCategories = ImageHelper::getAvailableCategories();
        if (!empty($dirCategories)) {
            $this->info('📁 Desde subdirectorios:');
            foreach ($dirCategories as $category) {
                $images = ImageHelper::scanImagesInDirectory("images/gallery/wedding");
                $categoryImages = array_filter($images, function($img) use ($category) {
                    return $img['category'] === $category;
                });
                $imageCount = count($categoryImages);
                $this->info("   - {$category} ({$imageCount} imágenes)");
            }
        }

        // Categorías desde base de datos
        $dbCategories = Gallery::getCategories();
        if (!empty($dbCategories)) {
            $this->newLine();
            $this->info('💾 Desde base de datos:');
            foreach ($dbCategories as $category) {
                $count = Gallery::where('category', $category)->count();
                $this->info("   - {$category} ({$count} registros)");
            }
        }

        if (empty($dirCategories) && empty($dbCategories)) {
            $this->warn('⚠️  No se encontraron categorías');
            $this->info('💡 Ejecuta: php artisan gallery:generate-thumbnails --create-dirs');
        }
    }

    private function processImage($imageData, $force = false)
    {
        $filename = $imageData['name'];
        $imagePath = $imageData['path'];
        $category = $imageData['category']; // Ahora viene del subdirectorio

        // Buscar en base de datos
        $galleryItem = Gallery::where('name', $filename)
                              ->where('category', $category)
                              ->first();

        // Obtener información de la imagen
        $imageInfo = ImageHelper::getImageInfo($imagePath);
        if (!$imageInfo) {
            throw new \Exception("No se puede obtener información de la imagen");
        }

        $created = false;
        $updated = false;

        if (!$galleryItem) {
            // Crear nuevo registro
            $galleryItem = new Gallery();
            $galleryItem->name = $filename;
            $galleryItem->category = $category;
            $galleryItem->path = $imagePath;
            $galleryItem->width = $imageInfo['width'];
            $galleryItem->height = $imageInfo['height'];
            $galleryItem->filesize = $imageInfo['filesize'];
            $created = true;
        } else {
            // Actualizar información si es necesario
            if ($galleryItem->width !== $imageInfo['width'] ||
                $galleryItem->height !== $imageInfo['height'] ||
                $galleryItem->filesize !== $imageInfo['filesize']) {

                $galleryItem->width = $imageInfo['width'];
                $galleryItem->height = $imageInfo['height'];
                $galleryItem->filesize = $imageInfo['filesize'];
                $updated = true;
            }
        }

        // Generar thumbnails si no existen o si se fuerza
        $needsThumbnails = $force || !$galleryItem->hasThumbnail();

        if ($needsThumbnails) {
            $thumbnails = ImageHelper::generateThumbnails($imagePath, $category, $filename);

            // Usar el thumbnail 'medium' como thumbnail principal
            if (isset($thumbnails['medium'])) {
                $galleryItem->thumbnail_path = $thumbnails['medium'];
                $updated = true;
            }
        }

        $galleryItem->save();

        return ['created' => $created, 'updated' => $updated];
    }

    private function cleanOrphanThumbnails()
    {
        $this->info('🧹 Limpiando thumbnails huérfanos...');
        $deletedCount = ImageHelper::cleanOrphanThumbnails();
        $this->info("🗑️  Eliminados {$deletedCount} thumbnails huérfanos");
    }

    private function showSummary($processed, $created, $updated, $errors)
    {
        $this->info('✅ Proceso completado!');
        $this->newLine();

        $this->table(['Métrica', 'Cantidad'], [
            ['Imágenes procesadas', $processed],
            ['Registros creados', $created],
            ['Registros actualizados', $updated],
            ['Errores', $errors],
        ]);

        if ($errors > 0) {
            $this->warn('⚠️  Se encontraron errores durante el proceso. Revisa los mensajes anteriores.');
        }

        // Mostrar estadísticas de la galería
        $this->newLine();
        $this->info('📊 Estadísticas de la galería:');

        $totalPhotos = Gallery::count();
        $categories = Gallery::getCategories();

        $this->info("Total de fotos: {$totalPhotos}");

        if (!empty($categories)) {
            $this->info("Categorías: " . implode(', ', $categories));

            foreach ($categories as $category) {
                $count = Gallery::where('category', $category)->count();
                $this->info("  - {$category}: {$count} fotos");
            }
        }
    }
}
