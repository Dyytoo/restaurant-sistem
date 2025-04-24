<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Menu Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h1 class="text-center">Menu Service Dashboard</h1>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Service URL:</strong> http://localhost:8001
                </div>
                
                <h3>API Endpoints:</h3>
                <ul class="list-group mb-4">
                    <li class="list-group-item">
                        <strong>GET /api/menus</strong> - Get all menu items
                    </li>
                    <li class="list-group-item">
                        <strong>GET /api/menus/{id}</strong> - Get single menu item
                    </li>
                    <li class="list-group-item">
                        <strong>GET /api/menus/category/{category}</strong> - Get menus by category
                    </li>
                    <li class="list-group-item">
                        <strong>POST /api/menus</strong> - Post New Menu
                    </li>
                    <li class="list-group-item">
                        <strong>DELETE /api/menus/{id}</strong> - Post New Menu
                    </li>
                </ul>
                
                <h3>Sample Menu Data:</h3>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="{{ route('menus.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Add New Menu
                    </a>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menus as $menu)
                        <tr>
                            <td>{{ $menu->id }}</td>
                            <td>{{ $menu->name }}</td>
                            <td>Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                            <td>{{ ucfirst($menu->category) }}</td>
                            <td>
                                <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" class="d-inline delete-menu-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-muted">
                Menu Service - Menyediakan tampilan untuk Data Menu
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-menu-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            if (confirm('Are you sure you want to delete this menu?')) {
                this.submit(); // fallback submit normal
            }
        });
    });
});

        </script>
</body>
</html>