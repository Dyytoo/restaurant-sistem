<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h1 class="text-center">Order Service Dashboard</h1>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Service URL:</strong> http://localhost:8002
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h3>API Endpoints:</h3>
                        <ul class="list-group mb-4">
                            <li class="list-group-item">
                                <strong>GET /api/orders</strong> - Get all orders
                            </li>
                            <li class="list-group-item">
                                <strong>POST /api/orders</strong> - Create new order
                            </li>
                            <li class="list-group-item">
                                <strong>DELETE /api/orders/{id}</strong> - Delete Order
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h3>Service Integration:</h3>
                        <div class="card">
                            <div class="card-body">
                                <p><strong>Consumes:</strong></p>
                                <ul>
                                    <li>User data dari UserService (port 8003)</li>
                                    <li>Menu data dari MenuService (port 8001)</li>
                                </ul>
                                <p><strong>Provides:</strong></p>
                                <ul>
                                    <li>Order data ke semua services</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h3 class="mt-4">Recent Orders:</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>User ID</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user_id }}</td>
                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge 
                                @if ($order->status === 'completed') bg-success
                                @elseif ($order->status === 'canceled') bg-danger
                                @else bg-warning
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($order['created_at'])
                                   ->setTimezone('Asia/Jakarta')
                                   ->format('d M Y H:i:s') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-muted">
                Order Service - Manages order transactions
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>