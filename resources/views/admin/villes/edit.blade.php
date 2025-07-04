@extends('admin.layouts.app')

@section('title', 'Modifier une ville')

@section('content')
    <h1>Modifier la ville</h1>

    <form action="{{ route('admin.villes.update', $ville) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" value="{{ $ville->nom }}" required>
        </div>

        <div class="mb-3">
            <label for="region_id" class="form-label">Région</label>
            <select name="region_id" id="region_id" class="form-select" required>
                @foreach($regions as $region)
                    <option value="{{ $region->id }}" {{ $ville->region_id == $region->id ? 'selected' : '' }}>
                        {{ $region->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('admin.villes.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection
