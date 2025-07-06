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

<form method="GET" action="{{ route('admin.provinces.index') }}" class="mb-3 d-flex gap-2 align-items-center">

    <input type="text" name="search" value="{{ request('search') }}" class="form-control w-25" placeholder="Rechercher une province...">
    <button type="submit" class="btn btn-outline-primary me-5">Rechercher</button>


    <select name="ville_id" class="form-select w-25" onchange="this.form.submit()">
        <option value="">-- Toutes les villes --</option>
        @foreach($villes as $ville)
            <option value="{{ $ville->id }}" {{ request('ville_id') == $ville->id ? 'selected' : '' }}>
                {{ $ville->nom }}
            </option>
        @endforeach
    </select>

    <select name="region_id" class="form-select w-25" onchange="this.form.submit()">
        <option value="">-- Toutes les régions --</option>
        @foreach($regions as $region)
            <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>
                {{ $region->nom }}
            </option>
        @endforeach
    </select>

</form>




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
