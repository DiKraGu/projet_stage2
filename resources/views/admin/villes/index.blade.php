@extends('admin.layouts.app')

@section('title', 'Villes')



@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Villes</h1>
        <a href="{{ route('admin.villes.create') }}" class="btn btn-primary">Ajouter ville</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<form method="GET" action="{{ route('admin.villes.index') }}" class="mb-3 d-flex gap-2">

 <select name="region_id" id="region_id" class="form-select" onchange="this.form.submit()">
    <option value="">-- Filtrer par région --</option>
    @foreach($regions as $region)
        <option value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'selected' : '' }}>
            {{ $region->nom }}
        </option>
    @endforeach
</select>

<select name="ville_id" id="ville_id" class="form-select" onchange="this.form.submit()">
    <option value="">-- Filtrer par ville --</option>
    @foreach($villesFiltrage as $villeOption)
        <option value="{{ $villeOption->id }}" {{ request('ville_id') == $villeOption->id ? 'selected' : '' }}>
            {{ $villeOption->nom }}
        </option>
    @endforeach
</select>


</form>


    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Région</th>
                <th>Nombre de provinces</th>
                <th>Nombre d'établissements</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($villes as $ville)
                <tr>
                    <td>{{ $ville->nom }}</td>
                    <td>{{ $ville->region->nom }}</td>
                    <td>{{ $ville->provinces_count }}</td>
                    <td>{{ $ville->provinces->flatMap->etablissements->count() }}</td>
                    <td>
                        <a href="{{ route('admin.villes.edit', $ville) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('admin.villes.destroy', $ville) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- {{ $villes->appends(request()->query())->links() }} --}}

{{-- {{ $villes->withQueryString()->links() }} --}}

    <div class="d-flex justify-content-end mt-4">
    {{ $villes->withQueryString()->links() }}
</div>

@endsection
