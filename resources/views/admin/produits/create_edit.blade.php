@extends('admin.layouts.app')

@section('title', isset($produit) ? 'Modifier Produit' : 'Ajouter Produit')

@section('content')
    <h1>{{ isset($produit) ? 'Modifier' : 'Ajouter' }} Produit</h1>

    <form action="{{ isset($produit) ? route('admin.produits.update', $produit) : route('admin.produits.store') }}" method="POST">
        @csrf
        @if(isset($produit)) @method('PUT') @endif

        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" value="{{ old('nom', $produit->nom ?? '') }}" required>
                        @error('nom')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ old('description', $produit->description ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <select name="categorie_id" class="form-select" required>
                <option value="">-- Choisir une catégorie --</option>
                @foreach($categories as $categorie)
                    <option value="{{ $categorie->id }}" {{ old('categorie_id', $produit->categorie_id ?? '') == $categorie->id ? 'selected' : '' }}>
                        {{ $categorie->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="fournisseur_id" class="form-label">Fournisseur</label>
            <select name="fournisseur_id" class="form-control" required>
                @foreach($fournisseurs as $fournisseur)
                    <option value="{{ $fournisseur->id }}" {{ (old('fournisseur_id', $produit->fournisseur_id ?? '') == $fournisseur->id) ? 'selected' : '' }}>
                        {{ $fournisseur->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Enregistrer</button>
    </form>
@endsection
