{{-- @extends('admin.layouts.app')

@section('title', 'Modifier le stock')

@section('content')
    <h2>Modifier le lot : {{ $stock->produit->nom }}</h2>

    <form method="POST" action="{{ route('admin.stocks.update', $stock) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Produit</label>
            <input type="text" class="form-control" value="{{ $stock->produit->nom }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Quantité reçue</label>
            <input type="number" class="form-control" value="{{ $stock->quantite_recue }}" disabled>
        </div>

        <div class="mb-3">
            <label for="quantite_disponible" class="form-label">Quantité disponible</label>
            <input type="number" name="quantite_disponible" id="quantite_disponible" class="form-control" required min="0" value="{{ old('quantite_disponible', $stock->quantite_disponible) }}">
            @error('quantite_disponible') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Prix unitaire</label>
            <input type="text" class="form-control" value="{{ number_format($stock->prix_unitaire, 2) }} DH" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Date de réception</label>
            <input type="date" class="form-control" value="{{ $stock->date_reception }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Date d'expiration</label>
            <input type="date" class="form-control" value="{{ $stock->date_expiration }}" disabled>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('admin.stocks.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection --}}

@extends('admin.layouts.app')

@section('title', 'Modifier le stock')

@section('content')
    <h2>Modifier le lot : {{ $stock->produit->nom }}</h2>

    <form method="POST" action="{{ route('admin.stocks.update', $stock) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="quantite_recue" class="form-label">Quantité reçue</label>
            <input type="number" name="quantite_recue" id="quantite_recue" class="form-control" required min="1"
                   value="{{ old('quantite_recue', $stock->quantite_recue) }}">
            @error('quantite_recue') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="quantite_disponible" class="form-label">Quantité disponible</label>
            <input type="number" name="quantite_disponible" id="quantite_disponible" class="form-control" required min="0"
                   value="{{ old('quantite_disponible', $stock->quantite_disponible) }}">
            @error('quantite_disponible') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="prix_unitaire" class="form-label">Prix unitaire (DH)</label>
            <input type="number" step="0.01" name="prix_unitaire" id="prix_unitaire" class="form-control" required
                   value="{{ old('prix_unitaire', $stock->prix_unitaire) }}">
            @error('prix_unitaire') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="date_reception" class="form-label">Date de réception</label>
            <input type="date" name="date_reception" id="date_reception" class="form-control" required
                   value="{{ old('date_reception', $stock->date_reception) }}">
            @error('date_reception') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="date_expiration" class="form-label">Date d'expiration</label>
            <input type="date" name="date_expiration" id="date_expiration" class="form-control" required
                   value="{{ old('date_expiration', $stock->date_expiration) }}">
            @error('date_expiration') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('admin.stocks.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection
