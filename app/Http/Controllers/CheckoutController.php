<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\Producto;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('checkout');
    }
    

public function store(Request $request)
{
    // Validar datos del formulario
    $request->validate([
        'nombre' => 'required|string',
        'dui' => 'required|regex:/^[0-9]{8}-[0-9]$/',
        'tarjeta' => 'required|regex:/^[0-9]{16}$/',
        'fecha' => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
        'correo' => 'required|email'
    ]);

    if (!$request->session()->has('carrito') || count($request->session()->get('carrito')) === 0) {
        return redirect()->route('carrito.index')->withErrors('El carrito está vacío.');
    }

    $carrito = $request->session()->get('carrito');

    DB::beginTransaction();

    try {
        // Guardar historial
        $historial = $request->session()->get('historial', []);
        $historial[] = [
            'fecha' => now()->format('d/m/Y H:i:s'),
            'nombre' => $request->nombre,
            'dui' => $request->dui,
            'correo' => $request->correo,
            'items' => $carrito
        ];
        $request->session()->put('historial', $historial);

        // Actualizar stock
        foreach ($carrito as $key => $item) {
            $productoId = $item['id'] ?? $key;
            $producto = Producto::find($productoId);

            if (!$producto) {
                // Producto no encontrado, lanzar error para cancelar todo
                throw new \Exception("Producto con ID {$productoId} no encontrado.");
            }

            $cantidad = $item['cantidad'] ?? 1;

            if ($producto->stock < $cantidad) {
                throw new \Exception("Stock insuficiente para el producto {$producto->nombre}.");
            }

            $producto->stock -= $cantidad;

            if ($producto->stock <= 0) {
                $producto->delete();
            } else {
                $producto->save();
            }
        }

        // Vaciar carrito solo si todo va bien
        $request->session()->forget('carrito');

        DB::commit();

        return redirect()->route('carrito.index')->with('success', 'Compra realizada con éxito');
    } catch (\Exception $e) {
        DB::rollBack();

        // Puedes loggear el error $e->getMessage()

        return redirect()->route('carrito.index')->withErrors('Error al procesar la compra: ' . $e->getMessage());
    }
}


}
