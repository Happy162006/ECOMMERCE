@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="text-center mb-4">Nuestras Categorías</h2>
    <div class="row">
        @foreach($categorias as $category)
            <div class="col-md-4 mb-4">
                <div class="card text-center p-3">
                    <i class="fa fa-folder-open fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">{{ $category->nombre }}</h5>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection