@extends('admin.layouts.app')

@section('title', 'Ajouter un lot')

@section('content')
    <h2>Ajouter un nouveau lot au stock</h2>

    <form method="POST" action="{{ route('admin.stocks.store') }}">
        @csrf

        <div class="mb-3">
            <label for="produit_id" class="form-label">Produit</label>
            <select name="produit_id" id="produit_id" class="form-select" required>
                <option value="">-- Sélectionner un produit --</option>
                @foreach($produits as $produit)
                    <option value="{{ $produit->id }}" {{ old('produit_id') == $produit->id ? 'selected' : '' }}>
                        {{ $produit->nom }}
                    </option>
                @endforeach
            </select>
            @error('produit_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="quantite_recue" class="form-label">Quantité reçue</label>
            <input type="number" name="quantite_recue" id="quantite_recue" class="form-control" required min="1" value="{{ old('quantite_recue') }}">
            @error('quantite_recue') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="prix_unitaire" class="form-label">Prix unitaire (DH)</label>
            <input type="number" name="prix_unitaire" id="prix_unitaire" class="form-control" required step="0.01" value="{{ old('prix_unitaire') }}">
            @error('prix_unitaire') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="date_expiration" class="form-label">Date d'expiration</label>
            <input type="date" name="date_expiration" id="date_expiration" class="form-control" required value="{{ old('date_expiration') }}">
            @error('date_expiration') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="date_reception" class="form-label">Date de réception</label>
            <input type="date" name="date_reception" id="date_reception" class="form-control" required value="{{ old('date_reception') }}">
            @error('date_reception') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="{{ route('admin.stocks.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
@endsection
