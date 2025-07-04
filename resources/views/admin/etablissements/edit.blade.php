@extends('admin.layouts.app')

@section('title', 'Modifier un établissement')

@section('content')
    <h2>Modifier un établissement</h2>

    <form action="{{ route('admin.etablissements.update', $etablissement) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" id="nom" class="form-control" value="{{ $etablissement->nom }}" required>
        </div>

        <div class="mb-3">
            <label for="region_id" class="form-label">Région</label>
            <select name="region_id" id="region_id" class="form-select" required>
                @foreach($regions as $region)
                    <option value="{{ $region->id }}" {{ $etablissement->region_id == $region->id ? 'selected' : '' }}>
                        {{ $region->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('admin.etablissements.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection
