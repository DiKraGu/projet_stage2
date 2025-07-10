@extends('admin.layouts.app')

@section('title', 'Modifier Livraison')

@section('content')
    <div class="container">
        <h1 class="mb-4">Modifier Livraison #{{ $livraison->id }}</h1>

        <form action="{{ route('admin.livraisons.update', $livraison) }}" method="POST">
            @csrf
            @method('PUT')

            @include('admin.livraisons.partials.form', ['submitText' => 'Mettre à jour'])
        </form>
    </div>
@endsection
