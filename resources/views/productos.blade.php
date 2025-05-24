@extends('layouts.app')

@section('content')
<div class="container py-5">
    @if($categorias->isNotEmpty())
        @foreach($categorias as $categoria)
            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h2 class="fw-bold text-uppercase">{{ $categoria->nombre }}</h2>
                </div>
                <div class="row g-4">
                    @foreach($categoria->productos as $producto)
                        @php 
                            $imagen = app('App\Http\Controllers\ProductoController')->getProductImage($producto->imagen); 
                        @endphp
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                            <div class="card h-100 rounded-4 shadow-sm">
                                <div class="bg-light rounded-top-4 overflow-hidden" style="height: 200px;">
                                    @if($imagen['exists'])
                                        <img src="{{ asset($imagen['path']) }}" alt="{{ $imagen['alt'] }}" class="w-100 h-100 object-fit-cover">
                                    @else
                                        <div class="d-flex flex-column justify-content-center align-items-center h-100 text-center text-muted">
                                            <i class="fas fa-image fa-4x mb-3 opacity-25"></i>
                                            <p class="small mb-0">Imagen no disponible</p>
                                        </div>
                                    @endif
                                </div>
                             <div class="card-body d-flex flex-column">
    <h5 class="card-title text-truncate">{{ $producto->nombre }}</h5>
    <p class="fs-5 fw-bold">${{ number_format($producto->precio, 2) }}</p>
    
    {{-- Mostrar stock --}}
    <p class="text-muted mb-2">Stock disponible: {{ $producto->stock }}</p>
    
    <form method="POST" action="{{ route('carrito.agregar') }}" class="mt-auto">
        @csrf
        <input type="hidden" name="id" value="{{ $producto->id }}">
        <input type="hidden" name="cantidad" value="1">
        <button type="submit" class="btn btn-dark w-100" {{ $producto->stock < 1 ? 'disabled' : '' }}>
            <i class="fas fa-cart-plus me-2"></i>Añadir al carrito
        </button>
    </form>
</div>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @else
        <div class="alert alert-warning text-center">
            No hay productos disponibles.
        </div>
    @endif
</div>
@endsection
