<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;

class ProductoController extends Controller
{
    public function index()
    {
        $categorias = Categoria::with([
            'productos' => function ($query) {
                $query->orderBy('precio', 'asc');
            }
        ])->get();

        return view('productos', compact('categorias'));
    }

    public function getProductImage($image_name)
    {
        $default_image = 'img/default.jpg';

        if (empty($image_name)) {
            return [
                'exists' => false,
                'path' => $default_image,
                'alt' => 'Imagen no disponible'
            ];
        }

        $clean_name = basename($image_name);
        $web_path = 'img/' . $clean_name;
        $full_path = public_path($web_path);
        $image_exists = file_exists($full_path);

        return [
            'exists' => $image_exists,
            'path' => $image_exists ? $web_path : $default_image,
            'alt' => $clean_name
        ];
    }


}