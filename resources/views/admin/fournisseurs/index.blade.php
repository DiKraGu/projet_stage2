@extends('admin.layouts.app')

@section('title', 'Fournisseurs')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Fournisseurs</h1>
        <a href="{{ route('admin.fournisseurs.create') }}" class="btn btn-primary">Ajouter fournisseur</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Contact</th>
                <th>Produits associés</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fournisseurs as $fournisseur)
                <tr>
                    <td>{{ $fournisseur->nom }}</td>
                    <td>{{ $fournisseur->contact }}</td>
                    <td>
                        @foreach($fournisseur->produits as $produit)
                            <span class="badge bg-secondary">{{ $produit->nom }}</span>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('admin.fournisseurs.edit', $fournisseur) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('admin.fournisseurs.destroy', $fournisseur) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
