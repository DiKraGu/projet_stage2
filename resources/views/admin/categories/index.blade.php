@extends('admin.layouts.app')

@section('title', 'Catégories')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Liste des catégories</h2>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Ajouter une Catégorie</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

<form method="GET" action="{{ route('admin.categories.index') }}" class="mb-3 d-flex gap-2 align-items-center">
    <input type="text" name="search" value="{{ request('search') }}" class="form-control w-25" placeholder="Rechercher une catégorie...">
    <button type="submit" class="btn btn-outline-primary">Rechercher</button>
</form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Nombre de produits</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $categorie)
                <tr>
                    <td>{{ $categorie->nom }}</td>
                    <td>{{ $categorie->produits_count }}</td>
                    <td>
                        <a href="{{ route('admin.categories.edit', $categorie) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('admin.categories.destroy', $categorie) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-end mt-4">
        {{ $categories->withQueryString()->links() }}
    </div>
@endsection
