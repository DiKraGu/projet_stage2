@extends('admin.layouts.app')

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
@endsection
