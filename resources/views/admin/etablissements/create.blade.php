{{-- @extends('admin.layouts.app')

@section('title', 'Ajouter un établissement')

@section('content')
    <h2>Ajouter un établissement</h2>

    <form action="{{ route('admin.etablissements.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" id="nom" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="region_id" class="form-label">Région</label>
            <select name="region_id" id="region_id" class="form-select" required>
                <option value="">-- Sélectionner une région --</option>
                @foreach($regions as $region)
                    <option value="{{ $region->id }}">{{ $region->nom }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Ajouter</button>
        <a href="{{ route('admin.etablissements.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection --}}

@extends('admin.layouts.app')

@section('title', 'Ajouter un établissement')

@section('content')
    <h2>Ajouter un établissement</h2>

    <form action="{{ route('admin.etablissements.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" id="nom" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="ville_id" class="form-label">Ville</label>
            <select name="ville_id" id="ville_id" class="form-select" required>
                <option value="">-- Sélectionner une ville --</option>
                @foreach($villes as $ville)
                    <option value="{{ $ville->id }}">
                        {{ $ville->nom }} — {{ $ville->region->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Ajouter</button>
        <a href="{{ route('admin.etablissements.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection
