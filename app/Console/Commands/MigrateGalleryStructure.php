<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\ImageHelper;

class MigrateGalleryStructure extends Command
{
    protected $signature = 'gallery:migrate-structure
                           {--dry-run : Solo mostrar qué se movería sin hacer cambios}
                           {--force : Sobrescribir archivos existentes}';

    protected $description = 'Migrar fotos de estructura plana a subdirectorios por categoría';

    public function handle()
    {
        $this->info('🔄 Migrando estructura de galería a subdirectorios...');

        $baseDir = 'images/gallery/wedding';
        $fullPath = public_path($baseDir);

        if (!is_dir($fullPath)) {
            $this->error("❌ No existe el directorio: {$fullPath}");
            return;
        }

        // Buscar archivos en el directorio base (sin subdirectorios)
        $flatFiles = $this->findFlatFiles($fullPath);

        if (empty($flatFiles)) {
            $this->info('✅ No se encontraron archivos para migrar');
            $this->info('ℹ️  La estructura ya está organizada por subdirectorios');
            return;
        }

        $this->info("📁 Encontrados " . count($flatFiles) . " archivos para migrar");

        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        if ($dryRun) {
            $this->info('🔍 MODO DRY-RUN: Solo mostrando cambios, sin ejecutar');
        }

        // Crear directorios necesarios
        $categoriesNeeded = [];
        foreach ($flatFiles as $file) {
            $category = ImageHelper::extractCategoryFromFilename($file['name']);
            $categoriesNeeded[] = $category;
        }
        $categoriesNeeded = array_unique($categoriesNeeded);

        foreach ($categoriesNeeded as $category) {
            $categoryDir = $fullPath . '/' . $category;
            if (!is_dir($categoryDir)) {
                if (!$dryRun) {
                    mkdir($categoryDir, 0755, true);
                }
                $this->info("📁 " . ($dryRun ? '[DRY-RUN] Crearía' : 'Creado') . " directorio: {$category}/");
            }
        }

        // Migrar archivos
        $moved = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($flatFiles as $file) {
            try {
                $result = $this->migrateFile($file, $dryRun, $force);

                if ($result['moved']) {
                    $moved++;
                } elseif ($result['skipped']) {
                    $skipped++;
                }

            } catch (\Exception $e) {
                $errors++;
                $this->error("❌ Error migrando {$file['name']}: " . $e->getMessage());
            }
        }

        // Mostrar resumen
        $this->newLine();
        $this->info('✅ Migración completada!');
        $this->table(['Métrica', 'Cantidad'], [
            ['Archivos movidos', $moved],
            ['Archivos omitidos', $skipped],
            ['Errores', $errors],
        ]);

        if (!$dryRun && $moved > 0) {
            $this->newLine();
            $this->info('🎯 Próximos pasos:');
            $this->info('1. Ejecuta: php artisan gallery:generate-thumbnails --force');
            $this->info('2. Verifica que la galería funciona correctamente');
            $this->info('3. Si todo está bien, puedes limpiar thumbnails antiguos con --clean');
        }

        if ($dryRun) {
            $this->newLine();
            $this->warn('⚠️  Este fue un DRY-RUN. Para ejecutar los cambios reales:');
            $this->warn('   php artisan gallery:migrate-structure');
        }
    }

    private function findFlatFiles($directory)
    {
        $files = [];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];

        $items = scandir($directory);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;

            $itemPath = $directory . '/' . $item;

            // Solo archivos que estén directamente en el directorio base
            if (is_file($itemPath)) {
                $extension = strtolower(pathinfo($item, PATHINFO_EXTENSION));
                if (in_array($extension, $allowedExtensions)) {
                    $files[] = [
                        'name' => $item,
                        'currentPath' => $itemPath,
                        'relativePath' => $item
                    ];
                }
            }
        }

        return $files;
    }

    private function migrateFile($file, $dryRun = false, $force = false)
    {
        $filename = $file['name'];
        $currentPath = $file['currentPath'];

        // Determinar categoría y nueva ubicación
        $category = ImageHelper::extractCategoryFromFilename($filename);
        $newDir = dirname($currentPath) . '/' . $category;
        $newPath = $newDir . '/' . $filename;

        $moved = false;
        $skipped = false;

        // Verificar si el archivo destino ya existe
        if (file_exists($newPath) && !$force) {
            $this->warn("⚠️  Omitido {$filename}: ya existe en {$category}/");
            $skipped = true;
        } else {
            if ($dryRun) {
                $this->info("🔄 [DRY-RUN] Movería: {$filename} → {$category}/{$filename}");
                $moved = true;
            } else {
                if (rename($currentPath, $newPath)) {
                    $this->info("✅ Movido: {$filename} → {$category}/{$filename}");
                    $moved = true;
                } else {
                    throw new \Exception("No se pudo mover el archivo");
                }
            }
        }

        return ['moved' => $moved, 'skipped' => $skipped];
    }
}
