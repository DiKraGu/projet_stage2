{{--
@extends('admin.layouts.app')

@section('title', 'Ajouter une région')

@section('content')
    <h2>Ajouter une région</h2>

    <form action="{{ route('admin.regions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nom" class="form-label">Nom de la région</label>
            <input type="text" name="nom" id="nom" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Ajouter</button>
        <a href="{{ route('admin.regions.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection --}}

@extends('admin.layouts.app')

@section('title', 'Ajouter une région')

@section('content')
    <h2>Ajouter une région</h2>

    <form action="{{ route('admin.regions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nom" class="form-label">Nom de la région</label>
            <select name="nom" id="nom" class="form-select" required>
                <option value="">-- Choisir une région --</option>
                <option value="Tanger-Tétouan-Al Hoceïma">Tanger-Tétouan-Al Hoceïma</option>
                <option value="L'Oriental">L'Oriental</option>
                <option value="Fès-Meknès">Fès-Meknès</option>
                <option value="Rabat-Salé-Kénitra">Rabat-Salé-Kénitra</option>
                <option value="Béni Mellal-Khénifra">Béni Mellal-Khénifra</option>
                <option value="Casablanca-Settat">Casablanca-Settat</option>
                <option value="Marrakech-Safi">Marrakech-Safi</option>
                <option value="Drâa-Tafilalet">Drâa-Tafilalet</option>
                <option value="Souss-Massa">Souss-Massa</option>
                <option value="Guelmim-Oued Noun">Guelmim-Oued Noun</option>
                <option value="Laâyoune-Sakia El Hamra">Laâyoune-Sakia El Hamra</option>
                <option value="Dakhla-Oued Ed-Dahab">Dakhla-Oued Ed-Dahab</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Ajouter</button>
        <a href="{{ route('admin.regions.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection
