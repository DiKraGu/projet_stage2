@extends('admin.layouts.app')

@section('title', 'Produits')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Produits</h1>
        <a href="{{ route('admin.produits.create') }}" class="btn btn-primary">Ajouter produit</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Fournisseur</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produits as $produit)
                <tr>
                    <td>{{ $produit->nom }}</td>
                    <td>{{ $produit->description }}</td>
                    <td>{{ $produit->fournisseur->nom }}</td>
                    <td>
                        <a href="{{ route('admin.produits.edit', $produit) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('admin.produits.destroy', $produit) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
