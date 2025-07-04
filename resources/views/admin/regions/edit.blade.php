{{-- @extends('admin.layouts.app')

@section('title', 'Modifier région')

@section('content')
    <h2>Modifier la région</h2>

    <form action="{{ route('admin.regions.update', $region) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" id="nom" class="form-control" value="{{ $region->nom }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('admin.regions.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection --}}

@extends('admin.layouts.app')

@section('title', 'Modifier région')

@section('content')
    <h2>Modifier la région</h2>

    <form action="{{ route('admin.regions.update', $region) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" id="nom" class="form-control" value="{{ $region->nom }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('admin.regions.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection
