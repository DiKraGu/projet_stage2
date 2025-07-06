{{--
@extends('admin.layouts.app')

@section('title', 'Modifier une ville')

@section('content')
    <h1>Modifier la ville</h1>

    <form action="{{ route('admin.villes.update', $ville) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nom" class="form-label">Ville</label>
            <select name="nom" id="nom" class="form-select" required>
                <option value="">-- Choisir une ville --</option>
                @php
                    $villes = [
                        'Casablanca', 'Rabat', 'Fès', 'Marrakech', 'Tanger',
                        'Agadir', 'Meknès', 'Oujda', 'Tétouan', 'El Jadida',
                        'Safi', 'Khouribga', 'Laâyoune', 'Errachidia', 'Beni Mellal'
                    ];
                @endphp
                @foreach($villes as $villeNom)
                    <option value="{{ $villeNom }}" {{ $ville->nom === $villeNom ? 'selected' : '' }}>
                        {{ $villeNom }}
                    </option>
                @endforeach
            </select>
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
@endsection --}}

@extends('admin.layouts.app')

@section('title', 'Modifier une ville')

@section('content')
    <h1>Modifier la ville</h1>

    <form action="{{ route('admin.villes.update', $ville) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nom" class="form-label">Ville</label>
            <select name="nom" id="nom" class="form-select" required>
                <option value="">-- Choisir une ville --</option>
                @foreach([
                    'Casablanca', 'Rabat', 'Fès', 'Marrakech', 'Tanger',
                    'Agadir', 'Meknès', 'Oujda', 'Tétouan', 'El Jadida',
                    'Safi', 'Khouribga', 'Laâyoune', 'Errachidia', 'Beni Mellal'
                ] as $villeNom)
                    <option value="{{ $villeNom }}" {{ old('nom', $ville->nom) === $villeNom ? 'selected' : '' }}>
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
                @foreach($regions as $region)
                    <option value="{{ $region->id }}" {{ old('region_id', $ville->region_id) == $region->id ? 'selected' : '' }}>
                        {{ $region->nom }}
                    </option>
                @endforeach
            </select>
            @error('region_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('admin.villes.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection


