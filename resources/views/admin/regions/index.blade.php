

@extends('admin.layouts.app')

@section('title', 'Liste des régions')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Régions</h2>
        <a href="{{ route('admin.regions.create') }}" class="btn btn-primary">Ajouter une région</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.regions.index') }}" class="mb-3 d-flex justify-content-start">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control w-25 me-2" placeholder="Rechercher une région...">
        <button type="submit" class="btn btn-outline-primary">Rechercher</button>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Nombre de villes</th>
                <th>Nombre de provinces</th>
                <th>Nombre d'établissements</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($regions as $region)
                <tr>
                    <td>{{ $region->nom }}</td>
                    <td>{{ $region->villes_count }}</td>
                    <td>{{ $region->villes->flatMap->provinces->count() }}</td>
                    {{-- <td>{{ $region->etablissements_count }}</td> --}}
                    <td>{{ $region->villes->flatMap->provinces->flatMap->etablissements->count() }}</td>

                    <td>
                        <a href="{{ route('admin.regions.edit', $region) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('admin.regions.destroy', $region) }}" method="POST" class="d-inline"
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
        {{ $regions->withQueryString()->links() }}
    </div>

@endsection

