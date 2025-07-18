@extends('admin.layouts.app')

@section('title', 'Produits')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Produits</h1>
        <a href="{{ route('admin.produits.create') }}" class="btn btn-primary">Ajouter produit</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.produits.index') }}" class="mb-3 d-flex gap-2 align-items-center flex-wrap">

        {{-- Recherche --}}
        <input type="text" name="search" value="{{ request('search') }}" class="form-control w-25" placeholder="Rechercher un produit...">

        {{-- Bouton Recherche --}}
        <button type="submit" class="btn btn-outline-primary me-5">Rechercher</button>

        {{-- Filtre Catégorie (auto submit) --}}
        <select name="categorie_id" class="form-select w-25" onchange="this.form.submit()">
            <option value="">-- Toutes les catégories --</option>
            @foreach($categories as $categorie)
                <option value="{{ $categorie->id }}" {{ request('categorie_id') == $categorie->id ? 'selected' : '' }}>
                    {{ $categorie->nom }}
                </option>
            @endforeach
        </select>

        {{-- Filtre Fournisseur (auto submit) --}}
        <select name="fournisseur_id" class="form-select w-25" onchange="this.form.submit()">
            <option value="">-- Tous les fournisseurs --</option>
            @foreach($fournisseurs as $fournisseur)
                <option value="{{ $fournisseur->id }}" {{ request('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
                    {{ $fournisseur->nom }}
                </option>
            @endforeach
        </select>

    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Catégorie</th>
                <th>Fournisseur</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produits as $produit)
                <tr>
                    <td>{{ $produit->nom }}</td>
                    <td>{{ $produit->description }}</td>
                    <td>{{ $produit->categorie->nom ?? '-' }}</td>
                    <td>{{ $produit->fournisseur->nom }}</td>
                    <td>
                        <a href="{{ route('admin.produits.edit', $produit) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('admin.produits.destroy', $produit) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-end mt-4">
        {{ $produits->withQueryString()->links() }}
    </div>
@endsection
