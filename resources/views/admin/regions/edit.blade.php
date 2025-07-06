{{--
@extends('admin.layouts.app')

@section('title', 'Modifier région')

@section('content')
    <h2>Modifier la région</h2>

    <form action="{{ route('admin.regions.update', $region) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" name="nom" id="nom" class="form-control" value="{{ $region->nom }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('admin.regions.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection --}}

@extends('admin.layouts.app')

@section('title', 'Modifier région')

@section('content')
    <h2>Modifier la région</h2>

    <form action="{{ route('admin.regions.update', $region) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <select name="nom" id="nom" class="form-select" required>
                <option value="">-- Choisir une région --</option>
                <option value="Tanger-Tétouan-Al Hoceïma" {{ $region->nom == 'Tanger-Tétouan-Al Hoceïma' ? 'selected' : '' }}>Tanger-Tétouan-Al Hoceïma</option>
                <option value="L'Oriental" {{ $region->nom == "L'Oriental" ? 'selected' : '' }}>L'Oriental</option>
                <option value="Fès-Meknès" {{ $region->nom == 'Fès-Meknès' ? 'selected' : '' }}>Fès-Meknès</option>
                <option value="Rabat-Salé-Kénitra" {{ $region->nom == 'Rabat-Salé-Kénitra' ? 'selected' : '' }}>Rabat-Salé-Kénitra</option>
                <option value="Béni Mellal-Khénifra" {{ $region->nom == 'Béni Mellal-Khénifra' ? 'selected' : '' }}>Béni Mellal-Khénifra</option>
                <option value="Casablanca-Settat" {{ $region->nom == 'Casablanca-Settat' ? 'selected' : '' }}>Casablanca-Settat</option>
                <option value="Marrakech-Safi" {{ $region->nom == 'Marrakech-Safi' ? 'selected' : '' }}>Marrakech-Safi</option>
                <option value="Drâa-Tafilalet" {{ $region->nom == 'Drâa-Tafilalet' ? 'selected' : '' }}>Drâa-Tafilalet</option>
                <option value="Souss-Massa" {{ $region->nom == 'Souss-Massa' ? 'selected' : '' }}>Souss-Massa</option>
                <option value="Guelmim-Oued Noun" {{ $region->nom == 'Guelmim-Oued Noun' ? 'selected' : '' }}>Guelmim-Oued Noun</option>
                <option value="Laâyoune-Sakia El Hamra" {{ $region->nom == 'Laâyoune-Sakia El Hamra' ? 'selected' : '' }}>Laâyoune-Sakia El Hamra</option>
                <option value="Dakhla-Oued Ed-Dahab" {{ $region->nom == 'Dakhla-Oued Ed-Dahab' ? 'selected' : '' }}>Dakhla-Oued Ed-Dahab</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('admin.regions.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection

