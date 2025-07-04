{{-- @extends('admin.layouts.app')

@section('title', 'Ajouter une région')

@section('content')
    <h2>Ajouter une région</h2>

    <form action="{{ route('admin.regions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nom" class="form-label">Nom de la région</label>
            <input type="text" name="nom" id="nom" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Ajouter</button>
        <a href="{{ route('admin.regions.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection --}}

@extends('admin.layouts.app')

@section('title', 'Ajouter une région')

@section('content')
    <h2>Ajouter une région</h2>

    <form action="{{ route('admin.regions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nom" class="form-label">Nom de la région</label>
            <input type="text" name="nom" id="nom" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Ajouter</button>
        <a href="{{ route('admin.regions.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection
