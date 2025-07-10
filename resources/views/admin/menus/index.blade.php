
@extends('admin.layouts.app')

@section('title', 'Liste des menus de la semaine')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Menus</h1>
    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">Créer un menu de la semaine</a>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Formulaire de recherche dynamique et filtres automatiques --}}
<form method="GET" action="{{ route('admin.menus.index') }}" id="filterForm" class="row g-3 mb-4 align-items-end">

    <div class="col-md-3">
        <input type="text" name="search" class="form-control" placeholder="Rechercher un établissement..." value="{{ request('search') }}" id="searchInput">
    </div>

    <div class="col-md-2">
        <select name="province_id" class="form-select" onchange="document.getElementById('filterForm').submit();">
            <option value="">-- Toutes les provinces --</option>
            @foreach($provinces as $province)
                <option value="{{ $province->id }}" {{ request('province_id') == $province->id ? 'selected' : '' }}>
                    {{ $province->nom }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-2">
        <select name="ville_id" class="form-select" onchange="document.getElementById('filterForm').submit();">
            <option value="">-- Toutes les villes --</option>
            @foreach($villes as $ville)
                <option value="{{ $ville->id }}" {{ request('ville_id') == $ville->id ? 'selected' : '' }}>
                    {{ $ville->nom }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- <div class="col-md-2">
        <input type="date" name="semaine" class="form-control" value="{{ request('semaine') }}" onchange="document.getElementById('filterForm').submit();">
    </div>

    <div class="col-md-2">
        <input type="date" name="semaine_fin" class="form-control" value="{{ request('semaine_fin') }}" onchange="document.getElementById('filterForm').submit();">
    </div> --}}

    <div class="col-md-2">
        <input type="date" name="selected_date" class="form-control" value="{{ request('selected_date') }}" onchange="document.getElementById('filterForm').submit();">
    </div>

</form>

{{-- Résultats --}}
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Établissement</th>
            <th>Province</th>
            <th>Ville</th>
            <th>Semaine</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($menus as $menu)
            <tr>
                <td>{{ $menu->etablissement->nom }}</td>
                <td>{{ $menu->etablissement->province->nom ?? '---' }}</td>
                <td>{{ $menu->etablissement->province->ville->nom ?? '---' }}</td>
                <td>
                    Du {{ \Carbon\Carbon::parse($menu->semaine)->format('d/m/Y') }}
                    au {{ \Carbon\Carbon::parse($menu->semaine_fin)->format('d/m/Y') }}
                </td>
                <td>
                    <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-sm btn-warning">Modifier</a>
                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Supprimer</button>
                    </form>
                    <a href="{{ route('admin.menus.pdf', $menu) }}" class="btn btn-sm btn-secondary">PDF</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">Aucun menu trouvé.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-end mt-4">
    {{ $menus->withQueryString()->links() }}
</div>

{{-- JS pour recherche dynamique --}}
<script>
    const searchInput = document.getElementById('searchInput');
    const filterForm = document.getElementById('filterForm');
    let debounceTimer;

    searchInput.addEventListener('input', function () {
        // Empêche de soumettre à chaque caractère
        clearTimeout(debounceTimer);

        // On attend 500ms après la dernière frappe
        debounceTimer = setTimeout(() => {
            filterForm.submit();
        }, 500);
    });
</script>


@endsection
