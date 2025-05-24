<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class CarritoController extends Controller
{
    public function index(Request $request)
    {
        return view('carrito');
    }

    public function agregar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:producto,id',
            'cantidad' => 'required|integer|min:1'
        ]);

        $producto = Producto::findOrFail($request->id);

        if ($request->cantidad > $producto->stock) {
            return back()->with('error', 'No hay suficiente stock disponible');
        }

        $carrito = $request->session()->get('carrito', []);

        if (isset($carrito[$request->id])) {
            $carrito[$request->id]['cantidad'] += $request->cantidad;
        } else {
            $carrito[$request->id] = [
                'nombre' => $producto->nombre,
                'precio' => $producto->precio,
                'cantidad' => $request->cantidad,
                'stock' => $producto->stock
            ];
        }

        $request->session()->put('carrito', $carrito);

        return redirect()->route('carrito.index');
    }

    public function actualizar(Request $request)
    {
        $request->validate([
            'update_id' => 'required|exists:producto,id',
            'update_cantidad' => 'required|integer|min:1'
        ]);

        $producto = Producto::findOrFail($request->update_id);

        if ($request->update_cantidad > $producto->stock) {
            return response()->json(['error' => 'No hay suficiente stock disponible'], 422);
        }

        $carrito = $request->session()->get('carrito', []);

        if (isset($carrito[$request->update_id])) {
            $carrito[$request->update_id]['cantidad'] = $request->update_cantidad;
            $carrito[$request->update_id]['stock'] = $producto->stock;
            $request->session()->put('carrito', $carrito);
        }

        $total = collect($carrito)->sum(function ($item) {
            return $item['precio'] * $item['cantidad'];
        });

        return response()->json([
            'success' => true,
            'cantidad' => $request->update_cantidad,
            'subtotal' => $producto->precio * $request->update_cantidad,
            'total' => $total,
        ]);
    }

    public function eliminar(Request $request, $id)
    {
        $carrito = $request->session()->get('carrito', []);
        unset($carrito[$id]);
        $request->session()->put('carrito', $carrito);

        return redirect()->route('carrito.index');
    }
}
