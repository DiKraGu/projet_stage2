@extends('admin.layouts.app')

@section('title', 'Liste des provinces')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Provinces</h2>
        <a href="{{ route('admin.provinces.create') }}" class="btn btn-primary">Ajouter une province</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Ville</th>
                <th>Région</th>
                <th>Nombre d'établissements</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($provinces as $province)
                <tr>
                    <td>{{ $province->nom }}</td>
                    <td>{{ $province->ville->nom }}</td>
                    <td>{{ $province->ville->region->nom }}</td>
                    <td>{{ $province->etablissements_count }}</td>
                    <td>
                        <a href="{{ route('admin.provinces.edit', $province) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('admin.provinces.destroy', $province) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
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
        {{ $provinces->withQueryString()->links() }}
    </div>
@endsection
