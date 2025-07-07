@extends('admin.layouts.app')

@section('title', 'Fournisseurs')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Fournisseurs</h1>
        <a href="{{ route('admin.fournisseurs.create') }}" class="btn btn-primary">Ajouter fournisseur</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.fournisseurs.index') }}" class="mb-3 d-flex gap-2 align-items-center">
        <input type="text" name="search" class="form-control w-25" placeholder="Rechercher par nom ou contact..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-outline-primary">Rechercher</button>
    </form>


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
                        <form action="{{ route('admin.fournisseurs.destroy', $fournisseur) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                            @csrf @method('DELETE')
                            {{-- <button type="submit" class="btn btn-sm btn-danger">Supprimer</button> --}}
                            <button class="btn btn-sm btn-danger">Supprimer</button>

                        </form>


                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-end mt-4">
        {{ $fournisseurs->withQueryString()->links() }}
    </div>

@endsection
