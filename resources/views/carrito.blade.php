@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Tu Carrito</h2>

    @if(session('carrito') && count(session('carrito')) > 0)
        <table class="table table-bordered table-hover align-middle text-center" id="carrito-table">
            <thead class="table-light">
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach(session('carrito') as $id => $item)
                    @php
                        $subtotal = $item['precio'] * $item['cantidad'];
                        $total += $subtotal;
                    @endphp
                    <tr data-id="{{ $id }}" data-stock="{{ $item['stock'] ?? 0 }}">
                        <td>{{ $item['nombre'] }}</td>
                        <td>${{ number_format($item['precio'], 2) }}</td>
                        <td>
                            <div class="d-flex align-items-center justify-content-center">
                                <input type="number" min="1" max="{{ $item['stock'] ?? 0 }}"
                                    class="form-control form-control-sm text-center cantidad-input"
                                    value="{{ $item['cantidad'] }}" style="width: 70px;" />
                                <button class="btn btn-sm btn-primary ms-2 actualizar-btn" title="Actualizar cantidad">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <small class="text-danger d-none cantidad-error" style="font-size: 0.75rem;">
                                Cantidad no disponible en stock.
                            </small>
                        </td>
                        <td class="subtotal">${{ number_format($subtotal, 2) }}</td>
                        <td>
                            <a href="{{ route('carrito.eliminar', $id) }}" class="btn btn-sm btn-danger">
                                <i class="fa fa-trash"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-end">
            <h4>Total: $<span id="total-general">{{ number_format($total, 2) }}</span></h4>
            <a href="{{ route('checkout.index') }}" class="btn btn-success"><i class="fa fa-credit-card"></i> Proceder a pagar</a>
        </div>
    @else
        <div class="alert alert-info text-center">
            Tu carrito está vacío.
        </div>
    @endif
     @if(session('historial') && count(session('historial')) > 0)
        <h3 class="mt-5">📜 Historial de Compras</h3>
        @foreach(array_reverse(session('historial')) as $index => $compra)
            <div class="card mb-3">
                <div class="card-header bg-light">
                    Compra #{{ count(session('historial')) - $index }} - {{ $compra['fecha'] }}
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0 text-center">
                        <thead class="table-secondary">
                            <tr>
                                <th>Producto</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $compraTotal = 0;
                                if (isset($compra['total'])) {
                                    $compraTotal = $compra['total'];
                                } elseif (isset($compra['items'])) {
                                    foreach ($compra['items'] as $item) {
                                        $compraTotal += $item['precio'] * $item['cantidad'];
                                    }
                                }
                            @endphp
                            @foreach($compra['items'] as $item)
                                <tr>
                                    <td>{{ $item['nombre'] }}</td>
                                    <td>${{ number_format($item['precio'], 2) }}</td>
                                    <td>{{ $item['cantidad'] }}</td>
                                    <td>${{ number_format($item['precio'] * $item['cantidad'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-end fw-bold">
                    Total: ${{ number_format($compraTotal, 2) }}
                </div>
            </div>
        @endforeach
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.actualizar-btn').forEach(button => {
        button.addEventListener('click', function () {
            const row = this.closest('tr');
            const id = row.getAttribute('data-id');
            const stock = parseInt(row.getAttribute('data-stock')) || 0;
            const inputCantidad = row.querySelector('.cantidad-input');
            const errorText = row.querySelector('.cantidad-error');
            const cantidad = parseInt(inputCantidad.value);

            if (cantidad < 1 || isNaN(cantidad)) {
                errorText.textContent = 'Cantidad mínima es 1.';
                errorText.classList.remove('d-none');
                return;
            }

            if (cantidad > stock) {
                errorText.textContent = 'Cantidad no disponible en stock.';
                errorText.classList.remove('d-none');
                return;
            }

            errorText.classList.add('d-none');

            const formData = new FormData();
            formData.append('update_id', id);
            formData.append('update_cantidad', cantidad);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("carrito.actualizar") }}', {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                if (!response.ok) throw new Error('Error en la respuesta');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const subtotalElem = row.querySelector('.subtotal');
                    subtotalElem.textContent = '$' + data.subtotal.toFixed(2);

                    document.getElementById('total-general').textContent = data.total.toFixed(2);
                } else {
                    alert(data.message || 'Error al actualizar carrito');
                }
            })
            .catch(() => alert('Error de red o del servidor al actualizar carrito.'));
        });
    });
});
</script>
@endsection
