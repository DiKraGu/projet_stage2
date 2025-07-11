{{--
@extends('admin.layouts.app')

@section('title', 'Modifier la livraison')

@section('content')
    <h1>Modifier Livraison #{{ $livraison->id }}</h1>

    <a href="{{ route('admin.livraisons.index') }}" class="btn btn-secondary mb-3">← Retour</a>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.livraisons.update', $livraison) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label class="form-label">Établissement</label>
            <input type="text" class="form-control" value="{{ $livraison->etablissement->nom }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Menu de la semaine</label>
            <input type="text" class="form-control"
                   value="Semaine du {{ \Carbon\Carbon::parse($livraison->menu->semaine)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($livraison->menu->semaine)->addDays(6)->format('d/m/Y') }}"
                   disabled>
        </div>

        <div class="mb-3">
            <label for="date_livraison" class="form-label">Date de livraison</label>
            <input type="date" name="date_livraison" id="date_livraison"
                   value="{{ old('date_livraison', $livraison->date_livraison) }}"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label for="statut" class="form-label">Statut</label>
            <select name="statut" id="statut" class="form-control">
                <option value="en_attente" {{ $livraison->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="livrée" {{ $livraison->statut === 'livrée' ? 'selected' : '' }}>Livrée</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Enregistrer</button>
    </form>
@endsection --}}

@extends('admin.layouts.app')

@section('title', 'Modifier la livraison')

@section('content')
    <h1>Modifier Livraison #{{ $livraison->id }}</h1>

    <a href="{{ route('admin.livraisons.index') }}" class="btn btn-secondary mb-3">← Retour</a>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.livraisons.update', $livraison) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label class="form-label">Établissement</label>
            <input type="text" class="form-control" value="{{ $livraison->etablissement->nom }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Menu de la semaine</label>
            <input type="text" class="form-control"
                   value="Semaine du {{ \Carbon\Carbon::parse($livraison->menu->semaine)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($livraison->menu->semaine)->addDays(6)->format('d/m/Y') }}"
                   disabled>
        </div>

        {{-- Liste des produits du menu --}}
        <div class="mb-3">
            <label class="form-label">Produits prévus dans ce menu :</label>
            <ul class="list-group">
                @foreach($livraison->menu->produits as $produit)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $produit->nom }}
                        <span class="badge bg-primary">{{ $produit->pivot->quantite_utilisee }} unités</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="mb-3 mt-4">
            <label for="date_livraison" class="form-label">Date de livraison</label>
            <input type="date" name="date_livraison" id="date_livraison"
                   value="{{ old('date_livraison', $livraison->date_livraison) }}"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label for="statut" class="form-label">Statut</label>
            <select name="statut" id="statut" class="form-control">
                <option value="en_attente" {{ $livraison->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="livrée" {{ $livraison->statut === 'livrée' ? 'selected' : '' }}>Livrée</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Enregistrer</button>
    </form>
@endsection
