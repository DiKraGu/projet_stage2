@extends('admin.layouts.app')

@section('title', 'Gestion du stock central')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Stock central</h1>
        <a href="{{ route('admin.stocks.create') }}" class="btn btn-primary">Ajouter un lot</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.stocks.index') }}" class="mb-3 d-flex gap-2 align-items-center flex-wrap">

    {{-- Barre de recherche par nom de produit --}}
    <input type="text" name="search" class="form-control w-25" placeholder="Rechercher un produit..."
           value="{{ request('search') }}">
    <button type="submit" class="btn btn-outline-primary">Rechercher</button>


    {{-- Sélecteur de filtre par état --}}
    <select name="etat" class="form-select w-25" onchange="this.form.submit()">
        <option value="">-- Tous les états --</option>
        <option value="actif" {{ request('etat') == 'actif' ? 'selected' : '' }}>Actif</option>
        <option value="perime" {{ request('etat') == 'perime' ? 'selected' : '' }}>Périmé</option>
        <option value="epuise" {{ request('etat') == 'epuise' ? 'selected' : '' }}>Épuisé</option>
    </select>

    {{-- Bouton de recherche --}}
</form>


    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité reçue</th>
                <th>Quantité dispo</th>
                <th>Prix unitaire</th>
                <th>Date réception</th>
                <th>Date expiration</th>
                <th>État</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lots as $lot)
                {{-- <tr class="{{ $lot->isExpired() ? 'table-danger' : '' }}"> --}}
                <tr class="
                    @if($lot->quantite_disponible == 0)
                        table-warning
                    @elseif($lot->isExpired())
                        table-danger
                    @endif
                ">
                    <td>{{ $lot->produit->nom }}</td>
                    <td>{{ $lot->quantite_recue }}</td>
                    <td>{{ $lot->quantite_disponible }}</td>
                    <td>{{ number_format($lot->prix_unitaire, 2) }} DH</td>
                    <td>{{ $lot->date_reception }}</td>
                    <td>{{ $lot->date_expiration }}</td>
                    <td>
                        @if ($lot->quantite_disponible == 0)
                            ⚠️ Épuisé
                        @elseif ($lot->isExpired())
                            ❌ Périmé
                        @else
                            ✅ Actif
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.stocks.edit', $lot) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form method="POST" action="{{ route('admin.stocks.destroy', $lot) }}" class="d-inline" onsubmit="return confirm('Supprimer ce lot ?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

