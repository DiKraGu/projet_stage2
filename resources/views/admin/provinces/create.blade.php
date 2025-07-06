@extends('admin.layouts.app')

@section('title', 'Ajouter une province')

@section('content')
    <h2>Ajouter une province</h2>

    <form action="{{ route('admin.provinces.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nom" class="form-label">Nom de la province</label>
            <input type="text" name="nom" id="nom" class="form-control" required>
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
