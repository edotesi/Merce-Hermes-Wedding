<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use ZipArchive;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category', 'todo');

        $photos = Gallery::byCategory($category)
            ->orderBy('created_at', 'asc')
            ->orderBy('order', 'asc')
            ->get();

        $categories = Gallery::getCategories();

        return view('gallery', compact('photos', 'categories', 'category'));
    }

    public function download($id)
    {
        $photo = Gallery::findOrFail($id);
        $filePath = public_path($photo->path);

        if (!file_exists($filePath)) {
            abort(404, 'Archivo no encontrado');
        }

        return Response::download($filePath, $photo->name);
    }

    public function downloadAll(Request $request)
    {
        $category = $request->get('category', 'todo');

        $photos = Gallery::byCategory($category)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($photos->isEmpty()) {
            abort(404, 'No hay fotos para descargar');
        }

        // Crear archivo ZIP temporal
        $zipFileName = 'fotos_boda_' . ($category !== 'todo' ? $category . '_' : '') . date('Y-m-d') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Crear directorio temporal si no existe
        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            foreach ($photos as $photo) {
                $filePath = public_path($photo->path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $photo->name);
                }
            }
            $zip->close();

            return Response::download($zipPath, $zipFileName)->deleteFileAfterSend(true);
        }

        abort(500, 'Error al crear el archivo ZIP');
    }
}
