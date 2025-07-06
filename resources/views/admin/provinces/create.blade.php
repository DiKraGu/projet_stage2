
@extends('admin.layouts.app')

@section('title', 'Ajouter une province')

@section('content')
    <h2>Ajouter une province</h2>

    <form action="{{ route('admin.provinces.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nom" class="form-label">Nom de la province</label>
            <select name="nom" id="nom" class="form-select" required>
                <option value="">-- Choisir une province --</option>

                <optgroup label="Salé (Préfecture de Salé)">
                    <option value="Salé">Salé</option>
                    <option value="Sidi Bouknadel">Sidi Bouknadel</option>
                    <option value="Bettana">Bettana</option>
                    <option value="Tabriquet">Tabriquet</option>
                    <option value="Laayayda">Laayayda</option>
                </optgroup>

                <optgroup label="Skhirate-Témara (Préfecture de Skhirate-Témara)">
                    <option value="Témara">Témara</option>
                    <option value="Skhirat">Skhirat</option>
                    <option value="Harhoura">Harhoura</option>
                    <option value="Ain Atiq">Ain Atiq</option>
                    <option value="Sidi Yahia des Zaërs">Sidi Yahia des Zaërs</option>
                    <option value="Sabbah">Sabbah</option>
                    <option value="Mers El Kheir">Mers El Kheir</option>
                </optgroup>

                <optgroup label="Kénitra (Province de Kénitra)">
                    <option value="Kénitra">Kénitra</option>
                    <option value="Mehdia">Mehdia</option>
                    <option value="Sidi Taibi">Sidi Taibi</option>
                    <option value="Sidi Yahya El Gharb">Sidi Yahya El Gharb</option>
                    <option value="Souk El Arbaa du Gharb">Souk El Arbaa du Gharb</option>
                    <option value="Lalla Mimouna">Lalla Mimouna</option>
                    <option value="Sidi Boubker El Haj">Sidi Boubker El Haj</option>
                    <option value="Arbaoua">Arbaoua</option>
                    <option value="Sidi Mohamed Lahmar">Sidi Mohamed Lahmar</option>
                </optgroup>

                <optgroup label="Khémisset (Province de Khémisset)">
                    <option value="Khémisset">Khémisset</option>
                    <option value="Tiflet">Tiflet</option>
                    <option value="Rommani">Rommani</option>
                    <option value="Oulmes">Oulmes</option>
                    <option value="Maaziz">Maaziz</option>
                    <option value="Tidass">Tidass</option>
                    <option value="Ait Ichou">Ait Ichou</option>
                    <option value="Sidi Allal El Bahraoui">Sidi Allal El Bahraoui</option>
                </optgroup>

                <optgroup label="Sidi Kacem (Province de Sidi Kacem)">
                    <option value="Sidi Kacem">Sidi Kacem</option>
                    <option value="Mechra Bel Ksiri">Mechra Bel Ksiri</option>
                    <option value="Jorf El Melha">Jorf El Melha</option>
                    <option value="Ain Dorij">Ain Dorij</option>
                    <option value="Had Kourt">Had Kourt</option>
                    <option value="Dar Gueddari">Dar Gueddari</option>
                    <option value="Zirara">Zirara</option>
                    <option value="Rissani">Rissani</option>
                </optgroup>

                <optgroup label="Sidi Slimane (Province de Sidi Slimane)">
                    <option value="Sidi Slimane">Sidi Slimane</option>
                    <option value="Dar Bel Amri">Dar Bel Amri</option>
                    <option value="Sidi Kacem El Jdid">Sidi Kacem El Jdid</option>
                    <option value="Boulaouane">Boulaouane</option>
                    <option value="M’saada">M’saada</option>
                    <option value="Ameur Chamalia">Ameur Chamalia</option>
                </optgroup>
            </select>
        </div>

        <div class="mb-3">
            <label for="ville_id" class="form-label">Ville</label>
            <select name="ville_id" id="ville_id" class="form-select" required>
                <option value="">-- Choisir une ville --</option>
                @foreach($villes as $ville)
                    <option value="{{ $ville->id }}">{{ $ville->nom }} — {{ $ville->region->nom }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Ajouter</button>
        <a href="{{ route('admin.provinces.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection
