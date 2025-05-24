@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">💳 Finalizar Compra</h2>

        @if(session()->has('carrito') && count(session('carrito')) > 0)
            <h4>Productos en tu carrito:</h4>
            <ul class="list-group mb-4">
                @foreach(session('carrito') as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $item['nombre'] ?? 'Producto' }} 
                        <span class="badge bg-primary rounded-pill">Cantidad: {{ $item['cantidad'] }}</span>
                    </li>
                @endforeach
            </ul>
        @else
            <p>No hay productos en tu carrito.</p>
        @endif

        <form method="POST" action="{{ route('checkout.store') }}" class="row g-3" id="checkoutForm">
            @csrf
            <div class="col-md-6">
                <label class="form-label">Nombre Completo</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">DUI</label>
                <input type="text" name="dui" id="dui" class="form-control" placeholder="00000000-0" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Número de Tarjeta</label>
                <input type="text" name="tarjeta" id="tarjeta" class="form-control" placeholder="1234567812345678" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Fecha de Vencimiento</label>
                <input type="text" name="fecha" id="fecha" class="form-control" placeholder="MM/AA" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="correo" id="correo" class="form-control" required>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success w-100 py-2"><i class="fa fa-check"></i> Confirmar Compra</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        const duiRegex = /^[0-9]{8}-[0-9]$/;
        const tarjetaRegex = /^[0-9]{16}$/;
        const fechaRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;
        const correoRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        let errores = [];

        const dui = document.getElementById('dui').value.trim();
        const tarjeta = document.getElementById('tarjeta').value.trim();
        const fecha = document.getElementById('fecha').value.trim();
        const correo = document.getElementById('correo').value.trim();

        if (!duiRegex.test(dui)) errores.push("DUI inválido (formato: 00000000-0)");
        if (!tarjetaRegex.test(tarjeta)) errores.push("Número de tarjeta inválido (16 dígitos)");
        if (!fechaRegex.test(fecha)) errores.push("Fecha de vencimiento inválida (formato: MM/AA)");
        if (!correoRegex.test(correo)) errores.push("Correo electrónico inválido");

        if (errores.length > 0) {
            e.preventDefault();
            alert("Errores encontrados:\n" + errores.join("\n"));
            return false;
        }

        return true;
    });
</script>
@endsection
