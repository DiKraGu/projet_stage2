{{--
@extends('admin.layouts.app')

@section('title', 'Liste des menus de la semaine')

@section('content')
    <h1 class="mb-4">Menus</h1>
    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary mb-3">Créer un menu de la semaine</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Établissement</th>
                <th>Semaine</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($menus as $menu)
                <tr>
                    <td>{{ $menu->id }}</td>
                    <td>{{ $menu->etablissement->nom }}</td>
                    <td>{{ $menu->semaine }}</td>
                    <td>
                        <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-sm btn-warning">Modifier</a>
                        <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</button>
                        </form>
                        <a href="{{ route('admin.menus.pdf', $menu) }}" class="btn btn-sm btn-secondary">PDF</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $menus->links() }}
@endsection --}}

@extends('admin.layouts.app')

@section('title', 'Liste des menus de la semaine')

@section('content')
    <h1 class="mb-4">Menus</h1>
    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary mb-3">Créer un menu de la semaine</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Établissement</th>
            <th>Province</th>
            <th>Ville</th>
            <th>Semaine</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($menus as $menu)
            <tr>
                <td>{{ $menu->id }}</td>
                <td>{{ $menu->etablissement->nom }}</td>
                <td>{{ $menu->etablissement->province->nom ?? '---' }}</td>
                <td>{{ $menu->etablissement->province->ville->nom ?? '---' }}</td>
                <td>
                    Du {{ \Carbon\Carbon::parse($menu->semaine)->format('d/m/Y') }}
                    au {{ \Carbon\Carbon::parse($menu->semaine_fin)->format('d/m/Y') }}
                </td>
                <td>
                    <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-sm btn-warning">Modifier</a>
                    <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</button>
                    </form>
                    <a href="{{ route('admin.menus.pdf', $menu) }}" class="btn btn-sm btn-secondary">PDF</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{ $menus->links() }}

@endsection
