@extends('admin.layouts.app')

@section('title', 'Ajouter une ville')

@section('content')
    <h1>Ajouter une ville</h1>

    <form action="{{ route('admin.villes.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nom" class="form-label">Ville</label>
            <select name="nom" id="nom" class="form-select" required>
                <option value="">-- Choisir une ville --</option>
                <option value="Casablanca">Casablanca</option>
                <option value="Rabat">Rabat</option>
                <option value="Fès">Fès</option>
                <option value="Marrakech">Marrakech</option>
                <option value="Tanger">Tanger</option>
                <option value="Agadir">Agadir</option>
                <option value="Meknès">Meknès</option>
                <option value="Oujda">Oujda</option>
                <option value="Tétouan">Tétouan</option>
                <option value="El Jadida">El Jadida</option>
                <option value="Safi">Safi</option>
                <option value="Khouribga">Khouribga</option>
                <option value="Laâyoune">Laâyoune</option>
                <option value="Errachidia">Errachidia</option>
                <option value="Beni Mellal">Beni Mellal</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="region_id" class="form-label">Région</label>
            <select name="region_id" id="region_id" class="form-select" required>
                <option value="">-- Choisir une région --</option>
                @foreach($regions as $region)
                    <option value="{{ $region->id }}">{{ $region->nom }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('admin.villes.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection
