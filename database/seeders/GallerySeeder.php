<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery;
use App\Helpers\ImageHelper;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🖼️  Iniciando seeder de galería...');

        // Limpiar tabla existente
        Gallery::truncate();

        // Escanear directorio de imágenes
        $images = ImageHelper::scanImagesInDirectory('images/gallery/wedding');

        if (empty($images)) {
            $this->command->warn('⚠️  No se encontraron imágenes en images/gallery/wedding/');
            $this->command->info('💡 Crea algunas imágenes de ejemplo:');
            $this->createSampleImages();
            return;
        }

        $this->command->info("📁 Encontradas " . count($images) . " imágenes");

        $processed = 0;
        $errors = 0;

        foreach ($images as $imageData) {
            try {
                $this->processImage($imageData);
                $processed++;

                $this->command->info("✅ Procesada: {$imageData['name']}");

            } catch (\Exception $e) {
                $errors++;
                $this->command->error("❌ Error procesando {$imageData['name']}: " . $e->getMessage());
            }
        }

        $this->command->info('');
        $this->command->info("✅ Proceso completado: {$processed} imágenes procesadas, {$errors} errores");

        // Mostrar estadísticas
        $this->showStatistics();
    }

    private function processImage($imageData)
    {
        $filename = $imageData['name'];
        $imagePath = $imageData['path'];
        $category = $imageData['category']; // Ahora viene del subdirectorio

        // Obtener información de la imagen
        $imageInfo = ImageHelper::getImageInfo($imagePath);
        if (!$imageInfo) {
            throw new \Exception("No se puede obtener información de la imagen");
        }

        // Generar thumbnails
        $thumbnails = ImageHelper::generateThumbnails($imagePath, $category, $filename);

        // Crear registro en base de datos
        Gallery::create([
            'name' => $filename,
            'category' => $category,
            'path' => $imagePath,
            'thumbnail_path' => $thumbnails['medium'] ?? null,
            'width' => $imageInfo['width'],
            'height' => $imageInfo['height'],
            'filesize' => $imageInfo['filesize'],
            'order' => 0
        ]);
    }

    private function createSampleImages()
    {
        $this->command->info('📝 Creando estructura de directorios y datos de ejemplo...');

        // Crear estructura de directorios
        $created = ImageHelper::createCategoryDirectories();
        if (!empty($created)) {
            $this->command->info("📁 Creados directorios: " . implode(', ', $created));
        }

        // Crear algunos registros de ejemplo (sin thumbnails reales)
        $sampleData = [
            // Ceremonia
            [
                'name' => 'entrada_novios.jpg',
                'category' => 'ceremonia',
                'path' => 'images/gallery/wedding/ceremonia/entrada_novios.jpg',
                'width' => 1920,
                'height' => 1280,
                'filesize' => 856432,
                'order' => 1
            ],
            [
                'name' => 'intercambio_anillos.jpg',
                'category' => 'ceremonia',
                'path' => 'images/gallery/wedding/ceremonia/intercambio_anillos.jpg',
                'width' => 1920,
                'height' => 1280,
                'filesize' => 923156,
                'order' => 2
            ],

            // Fiesta
            [
                'name' => 'primer_baile.jpg',
                'category' => 'fiesta',
                'path' => 'images/gallery/wedding/fiesta/primer_baile.jpg',
                'width' => 1920,
                'height' => 1280,
                'filesize' => 1024768,
                'order' => 1
            ],
            [
                'name' => 'pista_baile.jpg',
                'category' => 'fiesta',
                'path' => 'images/gallery/wedding/fiesta/pista_baile.jpg',
                'width' => 1920,
                'height' => 1280,
                'filesize' => 887234,
                'order' => 2
            ],

            // Fotomatón
            [
                'name' => 'grupo_amigos.jpg',
                'category' => 'fotomaton',
                'path' => 'images/gallery/wedding/fotomaton/grupo_amigos.jpg',
                'width' => 1920,
                'height' => 1280,
                'filesize' => 654321,
                'order' => 1
            ],
            [
                'name' => 'familia_divertida.jpg',
                'category' => 'fotomaton',
                'path' => 'images/gallery/wedding/fotomaton/familia_divertida.jpg',
                'width' => 1920,
                'height' => 1280,
                'filesize' => 789456,
                'order' => 2
            ],

            // Banquete
            [
                'name' => 'mesa_presidencial.jpg',
                'category' => 'banquete',
                'path' => 'images/gallery/wedding/banquete/mesa_presidencial.jpg',
                'width' => 1920,
                'height' => 1280,
                'filesize' => 945623,
                'order' => 1
            ],

            // Preboda
            [
                'name' => 'sesion_playa.jpg',
                'category' => 'preboda',
                'path' => 'images/gallery/wedding/preboda/sesion_playa.jpg',
                'width' => 1920,
                'height' => 1280,
                'filesize' => 756891,
                'order' => 1
            ],

            // Bienvenida
            [
                'name' => 'cocktail_bienvenida.jpg',
                'category' => 'bienvenida',
                'path' => 'images/gallery/wedding/bienvenida/cocktail_bienvenida.jpg',
                'width' => 1920,
                'height' => 1280,
                'filesize' => 876543,
                'order' => 1
            ]
        ];

        foreach ($sampleData as $data) {
            Gallery::create($data);
        }

        $this->command->info('✅ Datos de ejemplo creados');
        $this->command->info('📂 Estructura recomendada:');
        $this->command->info('public/images/gallery/wedding/');
        $this->command->info('├── ceremonia/     # Fotos de la ceremonia');
        $this->command->info('├── bienvenida/    # Cocktail de bienvenida');
        $this->command->info('├── banquete/      # Cena y brindis');
        $this->command->info('├── fiesta/        # Baile y diversión');
        $this->command->info('├── fotomaton/     # Fotos del fotomatón');
        $this->command->info('└── preboda/       # Sesión de preboda');
        $this->command->newLine();
        $this->command->warn('⚠️  Sube imágenes reales en estos directorios y ejecuta:');
        $this->command->warn('    php artisan gallery:generate-thumbnails');
    }

    private function showStatistics()
    {
        $totalPhotos = Gallery::count();
        $categories = Gallery::getCategories();

        $this->command->info('');
        $this->command->info('📊 Estadísticas de la galería:');
        $this->command->info("Total de fotos: {$totalPhotos}");

        if (!empty($categories)) {
            $this->command->info("Categorías encontradas: " . implode(', ', $categories));

            foreach ($categories as $category) {
                $count = Gallery::where('category', $category)->count();
                $this->command->info("  - {$category}: {$count} fotos");
            }
        }

        $withThumbnails = Gallery::whereNotNull('thumbnail_path')->count();
        $this->command->info("Fotos con thumbnails: {$withThumbnails}");
    }
}
