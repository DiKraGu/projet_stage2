@extends('admin.layouts.app')

@section('title', 'Villes')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Villes</h1>
        <a href="{{ route('admin.villes.create') }}" class="btn btn-primary">Ajouter ville</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Région</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($villes as $ville)
                <tr>
                    <td>{{ $ville->nom }}</td>
                    <td>{{ $ville->region->nom }}</td>
                    <td>
                        <a href="{{ route('admin.villes.edit', $ville) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('admin.villes.destroy', $ville) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
