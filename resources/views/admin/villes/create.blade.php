
@extends('admin.layouts.app')

@section('title', 'Ajouter une ville')

@section('content')
    <h1>Ajouter une ville</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.villes.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nom" class="form-label">Ville</label>
            <select name="nom" id="nom" class="form-select" required>
                <option value="">-- Choisir une ville --</option>
                @foreach([
                    'Casablanca', 'Rabat', 'Fès', 'Marrakech', 'Tanger',
                    'Agadir', 'Meknès', 'Oujda', 'Tétouan', 'El Jadida',
                    'Safi', 'Khouribga', 'Laâyoune', 'Errachidia', 'Beni Mellal'
                ] as $villeNom)
                    <option value="{{ $villeNom }}" {{ old('nom') === $villeNom ? 'selected' : '' }}>
                        {{ $villeNom }}
                    </option>
                @endforeach
            </select>
            @error('nom')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="region_id" class="form-label">Région</label>
            <select name="region_id" id="region_id" class="form-select" required>
                <option value="">-- Choisir une région --</option>
                @foreach($regions as $region)
                    <option value="{{ $region->id }}" {{ old('region_id') == $region->id ? 'selected' : '' }}>
                        {{ $region->nom }}
                    </option>
                @endforeach
            </select>
            @error('region_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('admin.villes.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection
