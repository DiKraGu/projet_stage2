
@extends('admin.layouts.app')

@section('title', 'Alertes de stock')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Alertes de Stock</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.alerts') }}" class="mb-3 d-flex gap-2 align-items-center flex-wrap">
        <select name="type" class="form-select w-25" onchange="this.form.submit()">
            <option value="">-- Tous les types d'alertes --</option>
            <option value="stock_faible" {{ request('type') == 'stock_faible' ? 'selected' : '' }}>Stock faible</option>
            <option value="bientot_perime" {{ request('type') == 'bientot_perime' ? 'selected' : '' }}>Bientôt périmé</option>
            <option value="perime" {{ request('type') == 'perime' ? 'selected' : '' }}>Périmé</option>
            <option value="epuise" {{ request('type') == 'epuise' ? 'selected' : '' }}>Épuisé</option>
        </select>

        <select name="statut" class="form-select w-25" onchange="this.form.submit()">
            {{-- <option value="">-- Tous les statuts --</option> --}}
            <option value="active" {{ request('statut') == 'active' ? 'selected' : '' }}>Actives</option>
            <option value="ignoree" {{ request('statut') == 'ignoree' ? 'selected' : '' }}>Ignorées</option>
        </select>
    </form>

    @if($alerts->isEmpty())
        <div class="alert alert-success">Aucune alerte pour le moment 🎉</div>
    @else
        <form action="{{ route('admin.alerts.bulk') }}" method="POST">
            @csrf
            {{-- <div id="bulk-actions" class="mb-2 d-none">
                <button name="action" value="ignore" class="btn btn-secondary btn-sm">Ignorer sélection</button>
                <button name="action" value="delete" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression des alertes sélectionnées ?')">Supprimer sélection</button>
            </div> --}}

            <div id="bulk-actions" class="mb-2 d-none">
                <button id="btn-ignore" name="action" value="ignore" class="btn btn-secondary btn-sm">Ignorer sélection</button>
                <button id="btn-delete" name="action" value="delete" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression des alertes sélectionnées ?')">Supprimer sélection</button>
            </div>


            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" onclick="toggleCheckboxes(this)">
                        </th>
                        <th>Produit</th>
                        <th>Quantité reçue</th>
                        <th>Quantité disponible</th>
                        <th>Prix unitaire</th>
                        <th>Date réception</th>
                        <th>Date expiration</th>
                        <th>Type d'alerte</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alerts as $alert)
                        @php
                            $lot = $alert->lot;
                            $rowClass = match($alert->type) {
                                'perime' => 'table-danger',
                                'bientot_perime', 'stock_faible' => 'table-warning text-dark',
                                'epuise' => 'table-secondary',
                                default => ''
                            };
                        @endphp
                        <tr class="{{ $rowClass }}">
                            <td>
                                {{-- <input type="checkbox" name="selected_alerts[]" value="{{ $alert->id }}"> --}}
                            <input type="checkbox" name="selected_alerts[]" value="{{ $alert->id }}" data-statut="{{ $alert->statut }}">

                            </td>
                            <td>{{ $lot->produit->nom }}</td>
                            <td>{{ in_array($alert->type, ['stock_faible', 'epuise']) ? $lot->quantite_recue : '' }}</td>
                            <td>{{ $lot->quantite_disponible }}</td>
                            <td>{{ number_format($lot->prix_unitaire, 2) }} DH</td>
                            <td>{{ in_array($alert->type, ['bientot_perime', 'perime']) ? $lot->date_reception : '' }}</td>
                            <td>{{ $lot->date_expiration }}</td>
                            <td>
                                @switch($alert->type)
                                    @case('stock_faible') ⚠️ Stock faible @break
                                    @case('bientot_perime') 🟠 Bientôt périmé @break
                                    @case('perime') ❌ Périmé @break
                                    @case('epuise') ⚫ Épuisé @break
                                @endswitch
                            </td>
                            <td>
                                @if($alert->statut === 'active')
                                    <form action="{{ route('admin.alerts.ignore', $alert->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-secondary">Ignorer</button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.alerts.destroy', $alert->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette alerte ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-end mt-3">
                {{ $alerts->withQueryString()->links() }}
            </div>
        </form>
    @endif

{{-- <script>
    const bulkActions = document.getElementById('bulk-actions');
    const checkboxes = document.querySelectorAll('input[name="selected_alerts[]"]');

    function updateBulkActionsVisibility() {
        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
        bulkActions.classList.toggle('d-none', !anyChecked);
    }

    // Quand on clique sur le "tout sélectionner"
    function toggleCheckboxes(masterCheckbox) {
        checkboxes.forEach(cb => cb.checked = masterCheckbox.checked);
        updateBulkActionsVisibility();
    }

    // Quand on clique sur une case individuelle
    checkboxes.forEach(cb => cb.addEventListener('change', updateBulkActionsVisibility));
</script> --}}

<script>
    const bulkActions = document.getElementById('bulk-actions');
    const btnIgnore = document.getElementById('btn-ignore');
    const checkboxes = document.querySelectorAll('input[name="selected_alerts[]"]');

    function updateBulkActionsVisibility() {
        const checked = Array.from(checkboxes).filter(cb => cb.checked);
        const anyChecked = checked.length > 0;

        // Affiche le conteneur s'il y a au moins une case cochée
        bulkActions.classList.toggle('d-none', !anyChecked);

        if (anyChecked) {
            // Vérifie si toutes les alertes sélectionnées sont actives
            const allActive = checked.every(cb => cb.dataset.statut === 'active');
            btnIgnore.classList.toggle('d-none', !allActive);
        }
    }

    // Tout sélectionner
    function toggleCheckboxes(masterCheckbox) {
        checkboxes.forEach(cb => cb.checked = masterCheckbox.checked);
        updateBulkActionsVisibility();
    }

    // Sélection individuelle
    checkboxes.forEach(cb => cb.addEventListener('change', updateBulkActionsVisibility));
</script>

@endsection
