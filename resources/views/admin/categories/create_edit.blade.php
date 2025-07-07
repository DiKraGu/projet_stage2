@extends('admin.layouts.app')

@section('title', isset($categorie) ? 'Modifier Catégorie' : 'Ajouter Catégorie')

@section('content')
    <h1>{{ isset($categorie) ? 'Modifier' : 'Ajouter' }} Catégorie</h1>

    <form action="{{ isset($categorie) ? route('admin.categories.update', $categorie) : route('admin.categories.store') }}" method="POST">
        @csrf
        @if(isset($categorie)) @method('PUT') @endif

        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" value="{{ old('nom', $categorie->nom ?? '') }}" required>
                    @error('nom')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Enregistrer</button>
    </form>
@endsection
