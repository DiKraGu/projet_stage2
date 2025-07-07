
@extends('admin.layouts.app')

@section('title', 'Liste des établissements')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Établissements</h2>
        <a href="{{ route('admin.etablissements.create') }}" class="btn btn-primary">Ajouter un établissement</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.etablissements.index') }}" class="mb-3 d-flex gap-2 align-items-center">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control w-25" placeholder="Rechercher un établissement...">
        <button type="submit" class="btn btn-outline-primary">Rechercher</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Province</th>
                <th>Ville</th>
                <th>Région</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($etablissements as $etablissement)
                <tr>
                    <td>{{ $etablissement->nom }}</td>
                    <td>{{ $etablissement->province->nom ?? '—' }}</td>
                    <td>{{ $etablissement->province->ville->nom ?? '—' }}</td>
                    <td>{{ $etablissement->province->ville->region->nom ?? '—' }}</td>
                    <td>
                        <a href="{{ route('admin.etablissements.edit', $etablissement) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('admin.etablissements.destroy', $etablissement) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Confirmer la suppression ?')">
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
        {{ $etablissements->withQueryString()->links() }}
    </div>

@endsection
