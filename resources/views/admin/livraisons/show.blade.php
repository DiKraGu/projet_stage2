{{--
@extends('admin.layouts.app')

@section('title', 'Détails de la livraison')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Détails livraison #{{ $livraison->id }}</h1>
    <a href="{{ route('admin.livraisons.index') }}" class="btn btn-secondary">← Retour à la liste</a>
</div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->has('exception'))
        <div class="alert alert-danger">Erreur technique : {{ $errors->first('exception') }}</div>
    @endif

    <div class="mb-4">
        <p><strong>Établissement :</strong> {{ $livraison->etablissement->nom }}</p>
        <p><strong>Semaine du menu :</strong> {{ \Carbon\Carbon::parse($livraison->menu->semaine)->format('d/m/Y') }}</p>
        <p><strong>Date de livraison :</strong> {{ $livraison->date_livraison ?? '-' }}</p>
    </div>

    <form action="{{ route('admin.livraisons.update', $livraison->id) }}" method="POST" class="mb-4">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label for="statut" class="form-label"><strong>Modifier le statut :</strong></label>
                <select name="statut" id="statut" class="form-control" onchange="this.form.submit()" {{ in_array($livraison->statut, ['livrée', 'annulée']) ? 'disabled' : '' }}>

                @if($livraison->statut === 'livrée')
                    <option value="livrée" selected>Livrée</option>
                @elseif($livraison->statut === 'annulée')
                    <option value="annulée" selected>Annulée</option>
                @else
                    <option value="en_attente" {{ $livraison->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="livrée" {{ $livraison->statut === 'livrée' ? 'selected' : '' }}>Livrée</option>
                    <option value="annulée" {{ $livraison->statut === 'annulée' ? 'selected' : '' }}>Annulée</option>
                @endif
            </select>
        </div>
    </form>

    <h3>Produits livrés</h3>

    @if ($livraison->details->isEmpty())
        <p class="text-muted">Aucun produit encore livré.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité livrée</th>
                    <th>Lot</th>
                    <th>Date d'expiration du lot</th>
                </tr>
            </thead>
            <tbody>
                @foreach($livraison->details as $detail)
                    <tr>
                        <td>{{ $detail->produit->nom }}</td>
                        <td>{{ $detail->quantite_livree }}</td>
                        <td>{{ $detail->lotStockAdmin->id ?? '-' }}</td>
                        <td>{{ $detail->lotStockAdmin->date_expiration ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection --}}

@extends('admin.layouts.app')

@section('title', 'Détails de la livraison')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Détails livraison #{{ $livraison->id }}</h1>
    <a href="{{ route('admin.livraisons.index') }}" class="btn btn-secondary">← Retour à la liste</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@if ($errors->has('exception'))
    <div class="alert alert-danger">Erreur technique : {{ $errors->first('exception') }}</div>
@endif

<div class="mb-4">
    <p><strong>Établissement :</strong> {{ $livraison->etablissement->nom }}</p>
    <p><strong>Semaine du menu :</strong> {{ \Carbon\Carbon::parse($livraison->menu->semaine)->format('d/m/Y') }}</p>
    <p><strong>Date de livraison :</strong> {{ $livraison->date_livraison ?? '-' }}</p>
</div>

{{-- Formulaire de modification du statut --}}
<form action="{{ route('admin.livraisons.update', $livraison->id) }}" method="POST" class="mb-4">
    @csrf
    @method('PATCH')

    <div class="mb-3">
        <label for="statut" class="form-label"><strong>Modifier le statut :</strong></label>
        <select name="statut" id="statut" class="form-control" onchange="this.form.submit()" {{ in_array($livraison->statut, ['livrée', 'annulée']) ? 'disabled' : '' }}>
            @if($livraison->statut === 'livrée')
                <option value="livrée" selected>Livrée</option>
            @elseif($livraison->statut === 'annulée')
                <option value="annulée" selected>Annulée</option>
            @else
                <option value="en_attente" {{ $livraison->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="livrée">Livrée</option>
                <option value="annulée">Annulée</option>
            @endif
        </select>
    </div>
</form>

<h3>Produits livrés</h3>

@if ($livraison->details->isEmpty())
    <p class="text-muted">Aucun produit encore livré.</p>
@else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité livrée</th>
                <th>Catégorie</th> {{-- Nouvelle colonne --}}
                <th>Lot</th>
                <th>Date d'expiration du lot</th>
            </tr>
        </thead>
        <tbody>
            @foreach($livraison->details as $detail)
                <tr>
                    <td>{{ $detail->produit->nom }}</td>
                    <td>{{ $detail->quantite_livree }}</td>
                    <td>{{ $detail->produit->categorie->nom ?? '-' }}</td> {{-- Affichage catégorie --}}
                    <td>{{ $detail->lotStockAdmin->id ?? '-' }}</td>
                    <td>{{ $detail->lotStockAdmin->date_expiration->format('Y-m-d') ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

@endsection
