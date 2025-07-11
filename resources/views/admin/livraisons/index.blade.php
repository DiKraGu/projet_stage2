{{--
@extends('admin.layouts.app')

@section('title', 'Livraisons')

@section('content')
<h1>Liste des livraisons</h1>

<a href="{{ route('admin.livraisons.create') }}" class="btn btn-primary mb-3">Planifier une nouvelle livraison</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
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
                <td>{{ $livraison->id }}</td>
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

                    @if($livraison->statut !== 'annulée')
                    <form action="{{ route('admin.livraisons.annuler', $livraison) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer l\'annulation ?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger btn-sm">Annuler</button>
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

@endsection --}}

@extends('admin.layouts.app')

@section('title', 'Livraisons')

@section('content')
<h1>Liste des livraisons</h1>

<a href="{{ route('admin.livraisons.create') }}" class="btn btn-primary mb-3">Planifier une nouvelle livraison</a>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
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
                <td>{{ $livraison->id }}</td>
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
                        {{-- Bouton modifier --}}
                        <a href="{{ route('admin.livraisons.edit', $livraison) }}" class="btn btn-secondary btn-sm">Modifier</a>


                        {{-- Bouton annuler --}}
                        <form action="{{ route('admin.livraisons.annuler', $livraison) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer l\'annulation ?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger btn-sm">Annuler</button>
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
@endsection
