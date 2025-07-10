<div class="mb-3">
    <label for="etablissement_id" class="form-label">Établissement</label>
    <select name="etablissement_id" class="form-control">
        @foreach($etablissements as $etablissement)
            <option value="{{ $etablissement->id }}" {{ old('etablissement_id', $livraison->etablissement_id ?? '') == $etablissement->id ? 'selected' : '' }}>
                {{ $etablissement->nom }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="semaine" class="form-label">Date de la semaine (lundi)</label>
    <input type="date" name="semaine" class="form-control" value="{{ old('semaine', $livraison->semaine ?? '') }}">
</div>

<div class="mb-3">
    <label for="date_livraison" class="form-label">Date de livraison (optionnel)</label>
    <input type="date" name="date_livraison" class="form-control" value="{{ old('date_livraison', $livraison->date_livraison ?? '') }}">
</div>

<h5>Produits à livrer</h5>
@foreach($produits as $produit)
    <div class="mb-2">
        <label>{{ $produit->nom }}</label>
        <input type="number" min="0" class="form-control" name="produits[{{ $produit->id }}][quantite]" value="{{ old('produits.'.$produit->id.'.quantite', 0) }}">
    </div>
@endforeach

<button type="submit" class="btn btn-primary mt-3">{{ $submitText }}</button>
