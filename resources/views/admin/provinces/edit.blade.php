{{-- @extends('admin.layouts.app')

@section('title', 'Modifier une province')

@section('content')
    <h2>Modifier la province</h2>

    <form action="{{ route('admin.provinces.update', $province) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" id="nom" class="form-control" value="{{ $province->nom }}" required>
        </div>

        <div class="mb-3">
            <label for="ville_id" class="form-label">Ville</label>
            <select name="ville_id" id="ville_id" class="form-select" required>
                @foreach($villes as $ville)
                    <option value="{{ $ville->id }}" {{ $province->ville_id == $ville->id ? 'selected' : '' }}>
                        {{ $ville->nom }} ({{ $ville->region->nom }})
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('admin.provinces.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection --}}

@extends('admin.layouts.app')

@section('title', 'Modifier une province')

@section('content')
    <h2>Modifier la province</h2>

    <form action="{{ route('admin.provinces.update', $province) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nom" class="form-label">Nom de la province</label>
            <select name="nom" id="nom" class="form-select" required>
                <option value="">-- Choisir une province --</option>

                <optgroup label="Salé (Préfecture de Salé)">
                    <option value="Salé" {{ $province->nom == 'Salé' ? 'selected' : '' }}>Salé</option>
                    <option value="Sidi Bouknadel" {{ $province->nom == 'Sidi Bouknadel' ? 'selected' : '' }}>Sidi Bouknadel</option>
                    <option value="Bettana" {{ $province->nom == 'Bettana' ? 'selected' : '' }}>Bettana</option>
                    <option value="Tabriquet" {{ $province->nom == 'Tabriquet' ? 'selected' : '' }}>Tabriquet</option>
                    <option value="Laayayda" {{ $province->nom == 'Laayayda' ? 'selected' : '' }}>Laayayda</option>
                </optgroup>

                <optgroup label="Skhirate-Témara (Préfecture de Skhirate-Témara)">
                    <option value="Témara" {{ $province->nom == 'Témara' ? 'selected' : '' }}>Témara</option>
                    <option value="Skhirat" {{ $province->nom == 'Skhirat' ? 'selected' : '' }}>Skhirat</option>
                    <option value="Harhoura" {{ $province->nom == 'Harhoura' ? 'selected' : '' }}>Harhoura</option>
                    <option value="Ain Atiq" {{ $province->nom == 'Ain Atiq' ? 'selected' : '' }}>Ain Atiq</option>
                    <option value="Sidi Yahia des Zaërs" {{ $province->nom == 'Sidi Yahia des Zaërs' ? 'selected' : '' }}>Sidi Yahia des Zaërs</option>
                    <option value="Sabbah" {{ $province->nom == 'Sabbah' ? 'selected' : '' }}>Sabbah</option>
                    <option value="Mers El Kheir" {{ $province->nom == 'Mers El Kheir' ? 'selected' : '' }}>Mers El Kheir</option>
                </optgroup>

                <optgroup label="Kénitra (Province de Kénitra)">
                    <option value="Kénitra" {{ $province->nom == 'Kénitra' ? 'selected' : '' }}>Kénitra</option>
                    <option value="Mehdia" {{ $province->nom == 'Mehdia' ? 'selected' : '' }}>Mehdia</option>
                    <option value="Sidi Taibi" {{ $province->nom == 'Sidi Taibi' ? 'selected' : '' }}>Sidi Taibi</option>
                    <option value="Sidi Yahya El Gharb" {{ $province->nom == 'Sidi Yahya El Gharb' ? 'selected' : '' }}>Sidi Yahya El Gharb</option>
                    <option value="Souk El Arbaa du Gharb" {{ $province->nom == 'Souk El Arbaa du Gharb' ? 'selected' : '' }}>Souk El Arbaa du Gharb</option>
                    <option value="Lalla Mimouna" {{ $province->nom == 'Lalla Mimouna' ? 'selected' : '' }}>Lalla Mimouna</option>
                    <option value="Sidi Boubker El Haj" {{ $province->nom == 'Sidi Boubker El Haj' ? 'selected' : '' }}>Sidi Boubker El Haj</option>
                    <option value="Arbaoua" {{ $province->nom == 'Arbaoua' ? 'selected' : '' }}>Arbaoua</option>
                    <option value="Sidi Mohamed Lahmar" {{ $province->nom == 'Sidi Mohamed Lahmar' ? 'selected' : '' }}>Sidi Mohamed Lahmar</option>
                </optgroup>

                <optgroup label="Khémisset (Province de Khémisset)">
                    <option value="Khémisset" {{ $province->nom == 'Khémisset' ? 'selected' : '' }}>Khémisset</option>
                    <option value="Tiflet" {{ $province->nom == 'Tiflet' ? 'selected' : '' }}>Tiflet</option>
                    <option value="Rommani" {{ $province->nom == 'Rommani' ? 'selected' : '' }}>Rommani</option>
                    <option value="Oulmes" {{ $province->nom == 'Oulmes' ? 'selected' : '' }}>Oulmes</option>
                    <option value="Maaziz" {{ $province->nom == 'Maaziz' ? 'selected' : '' }}>Maaziz</option>
                    <option value="Tidass" {{ $province->nom == 'Tidass' ? 'selected' : '' }}>Tidass</option>
                    <option value="Ait Ichou" {{ $province->nom == 'Ait Ichou' ? 'selected' : '' }}>Ait Ichou</option>
                    <option value="Sidi Allal El Bahraoui" {{ $province->nom == 'Sidi Allal El Bahraoui' ? 'selected' : '' }}>Sidi Allal El Bahraoui</option>
                </optgroup>

                <optgroup label="Sidi Kacem (Province de Sidi Kacem)">
                    <option value="Sidi Kacem" {{ $province->nom == 'Sidi Kacem' ? 'selected' : '' }}>Sidi Kacem</option>
                    <option value="Mechra Bel Ksiri" {{ $province->nom == 'Mechra Bel Ksiri' ? 'selected' : '' }}>Mechra Bel Ksiri</option>
                    <option value="Jorf El Melha" {{ $province->nom == 'Jorf El Melha' ? 'selected' : '' }}>Jorf El Melha</option>
                    <option value="Ain Dorij" {{ $province->nom == 'Ain Dorij' ? 'selected' : '' }}>Ain Dorij</option>
                    <option value="Had Kourt" {{ $province->nom == 'Had Kourt' ? 'selected' : '' }}>Had Kourt</option>
                    <option value="Dar Gueddari" {{ $province->nom == 'Dar Gueddari' ? 'selected' : '' }}>Dar Gueddari</option>
                    <option value="Zirara" {{ $province->nom == 'Zirara' ? 'selected' : '' }}>Zirara</option>
                    <option value="Rissani" {{ $province->nom == 'Rissani' ? 'selected' : '' }}>Rissani</option>
                </optgroup>

                <optgroup label="Sidi Slimane (Province de Sidi Slimane)">
                    <option value="Sidi Slimane" {{ $province->nom == 'Sidi Slimane' ? 'selected' : '' }}>Sidi Slimane</option>
                    <option value="Dar Bel Amri" {{ $province->nom == 'Dar Bel Amri' ? 'selected' : '' }}>Dar Bel Amri</option>
                    <option value="Sidi Kacem El Jdid" {{ $province->nom == 'Sidi Kacem El Jdid' ? 'selected' : '' }}>Sidi Kacem El Jdid</option>
                    <option value="Boulaouane" {{ $province->nom == 'Boulaouane' ? 'selected' : '' }}>Boulaouane</option>
                    <option value="M’saada" {{ $province->nom == 'M’saada' ? 'selected' : '' }}>M’saada</option>
                    <option value="Ameur Chamalia" {{ $province->nom == 'Ameur Chamalia' ? 'selected' : '' }}>Ameur Chamalia</option>
                </optgroup>
            </select>

            @error('nom')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="ville_id" class="form-label">Ville</label>
            <select name="ville_id" id="ville_id" class="form-select" required>
                <option value="">-- Choisir une ville --</option>
                @foreach($villes as $ville)
                    <option value="{{ $ville->id }}" {{ $province->ville_id == $ville->id ? 'selected' : '' }}>
                        {{ $ville->nom }} — {{ $ville->region->nom }}
                    </option>
                @endforeach
            </select>

            @error('ville_id')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('admin.provinces.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection
