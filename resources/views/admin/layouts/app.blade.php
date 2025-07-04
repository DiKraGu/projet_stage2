<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Admin')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            overflow-x: hidden;
        }

        .sidebar {
            width: 240px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            padding-top: 60px;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar.collapsed {
            width: 60px;
        }

        .sidebar .nav-link {
            color: #fff;
        }

        .sidebar .nav-link:hover {
            background-color: #495057;
        }

        .sidebar.collapsed .nav-text {
            display: none;
        }

        .content {
            margin-left: 240px;
            transition: all 0.3s;
        }

        .content.collapsed {
            margin-left: 60px;
        }

        .toggle-btn {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1001;
            background-color: #343a40;
            border: none;
            color: #fff;
            padding: 6px 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <div>
            <ul class="nav flex-column">
                <li class="nav-item px-3">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item px-3">
                    <a href="{{ route('admin.regions.index') }}" class="nav-link">
                        <span class="nav-text">Régions</span>
                    </a>
                </li>
                <li class="nav-item px-3">
                    <a href="{{ route('admin.villes.index') }}" class="nav-link">
                        <span class="nav-text">Villes</span>
                    </a>
                </li>
                <li class="nav-item px-3">
                    <a href="{{ route('admin.etablissements.index') }}" class="nav-link">
                        <span class="nav-text">Établissements</span>
                    </a>
                </li>
                <li class="nav-item px-3">
                <a href="{{ route('admin.fournisseurs.index') }}" class="nav-link">
                    <span class="nav-text">Fournisseurs</span>
                </a>
            </li>
            <li class="nav-item px-3">
                <a href="{{ route('admin.produits.index') }}" class="nav-link">
                    <span class="nav-text">Produits</span>
                </a>
            </li>

            {{-- <li class="nav-item mt-2">
                <a class="nav-link" href="{{ route('admin.villes.index') }}">🏙️ Villes</a>
            </li> --}}
            </ul>
        </div>

        <div class="px-3 pb-4">
            @auth('admin')
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light w-100">
                    <span class="nav-text">Déconnexion</span>
                </button>
            </form>
            @endauth
        </div>
    </div>

    <!-- Toggle button -->
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

    <!-- Main content -->
    <div id="mainContent" class="content py-4 px-4">
        @yield('content')
    </div>

    <!-- JS -->
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.getElementById('mainContent').classList.toggle('collapsed');
        }
    </script>
</body>
</html>
