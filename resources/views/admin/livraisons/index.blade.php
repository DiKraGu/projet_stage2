
@extends('admin.layouts.app')

@section('title', 'Livraisons')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Liste des livraisons</h1>
    <a href="{{ route('admin.livraisons.create') }}" class="btn btn-primary">Planifier une nouvelle livraison</a>
</div>

{{-- Barre de recherche / filtres --}}
<form id="filter-form" method="GET" action="{{ route('admin.livraisons.index') }}" class="row mb-4">
    <div class="col-md-3">
        <input type="text" name="etablissement" value="{{ request('etablissement') }}" class="form-control"
               placeholder="Nom de l’établissement" oninput="submitFilter()">
    </div>
    <div class="col-md-2">
        <input type="date" name="menu_date" value="{{ request('menu_date') }}" class="form-control" onchange="submitFilter()">
    </div>
    <div class="col-md-2">
        <input type="date" name="livraison_date" value="{{ request('livraison_date') }}" class="form-control" onchange="submitFilter()">
    </div>
    <div class="col-md-2">
        <select name="statut" class="form-select" onchange="submitFilter()">
            <option value="">-- Tous les statuts --</option>
            <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
            <option value="livrée" {{ request('statut') === 'livrée' ? 'selected' : '' }}>Livrée</option>
            <option value="annulée" {{ request('statut') === 'annulée' ? 'selected' : '' }}>Annulée</option>
        </select>
    </div>
</form>

{{-- Flash messages --}}
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- Table des livraisons --}}
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Établissement</th>
            <th>Semaine du menu</th>
            <th>Date de livraison</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($livraisons as $livraison)
            <tr>
                <td>{{ $livraison->etablissement->nom }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($livraison->menu->semaine)->format('d/m/Y') }}
                    -
                    {{ \Carbon\Carbon::parse($livraison->menu->semaine)->addDays(6)->format('d/m/Y') }}
                </td>
                <td>{{ $livraison->date_livraison ?? '-' }}</td>
                <td>
                    @if($livraison->statut === 'en_attente')
                        <span class="badge bg-warning text-dark">En attente</span>
                    @elseif($livraison->statut === 'livrée')
                        <span class="badge bg-success">Livrée</span>
                    @elseif($livraison->statut === 'annulée')
                        <span class="badge bg-danger">Annulée</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.livraisons.show', $livraison) }}" class="btn btn-info btn-sm">Voir</a>

                    @if($livraison->statut === 'en_attente')
                        <a href="{{ route('admin.livraisons.edit', $livraison) }}" class="btn btn-secondary btn-sm">Modifier</a>

                        <form action="{{ route('admin.livraisons.annuler', $livraison) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer l\'annulation ?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger btn-sm">Annuler</button>
                        </form>
                    @elseif($livraison->statut === 'annulée')
                        <form action="{{ route('admin.livraisons.destroy', $livraison) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer définitivement ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">Supprimer</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">Aucune livraison planifiée</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{ $livraisons->links() }}

{{-- JS pour soumission automatique --}}
<script>
    function submitFilter() {
        document.getElementById('filter-form').submit();
    }
</script>
@endsection
