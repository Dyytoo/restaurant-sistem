<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Order System</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <style>
        .order-item-card {
            transition: all 0.3s ease;
        }
        .order-item-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .badge {
            font-size: 0.9em;
            padding: 5px 10px;
        }

        .dropdown-toggle.status-toggle {
        min-width: 100px;
        color: white;
        }
        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
        }
        .dropdown-item.active {
            background-color: rgba(0, 0, 0, 0.05);
        }
        .dropdown-item .badge {
            width: 70px;
            display: inline-block;
            text-align: center;
        }
    </style>
</head>
<body>

    

    <div class="container mt-5">
        <h1 class="mb-4">Restaurant Order System</h1>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        

        <div class="row">
            <div class="col-md-6">
                <h2>Menu Items</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($menus as $menu)
                            <tr>
                                <td>{{ $menu['name'] }}</td>
                                <td>Rp {{ number_format($menu['price'], 0, ',', '.') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary add-to-cart" 
                                            data-menu-id="{{ $menu['id'] }}"
                                            data-menu-name="{{ $menu['name'] }}"
                                            data-menu-price="{{ $menu['price'] }}">
                                        Add to Cart
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="col-md-6">
                <h2>Order Form</h2>
                <form method="POST" action="{{ route('place.order') }}">
                    @csrf
                    <div class="form-group">
                        <label for="user_id">Customer</label>
                        <select class="form-control" id="user_id" name="user_id" required>
                            <option value="">Select Customer</option>
                            @foreach($users as $user)
                                <option value="{{ $user['id'] }}">{{ $user['name'] }} ({{ $user['email'] }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <h4 class="mt-4">Order Items</h4>
                    <div id="order-items-container">
                        <!-- Items will be added here dynamically -->
                    </div>
                    
                    <button type="submit" class="btn btn-primary mt-3">Place Order</button>
                </form>
            </div>
        </div>
    </div>

    {{-- List Order --}}

    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-md-12">
                <h2>Recent Orders</h2>
                <table id="ordersTable" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>{{ $order['id'] }}</td>
                            <td>
                                @php
                                    // Cari nama user berdasarkan user_id
                                    $user = collect($users)->firstWhere('id', $order['user_id']);
                                @endphp
                                {{ $user['name'] ?? 'Unknown' }} (ID: {{ $order['user_id'] }})
                            </td>
                            <td>
                                <ul class="list-unstyled">
                                    @foreach($order['items'] as $item)
                                        @php
                                            // Cari nama menu berdasarkan menu_id
                                            $menu = collect($menus)->firstWhere('id', $item['menu_id']);
                                        @endphp
                                        <li>
                                            {{ $menu['name'] ?? 'Menu #'.$item['menu_id'] }} 
                                            (Qty: {{ $item['quantity'] }})
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>Rp {{ number_format($order['total_amount'], 0, ',', '.') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm dropdown-toggle 
                                                  @if($order['status'] === 'completed') btn-success
                                                  @elseif($order['status'] === 'canceled') btn-danger
                                                  @else btn-warning @endif"
                                            type="button" id="statusDropdown{{ $order['id'] }}" 
                                            data-bs-toggle="dropdown">
                                        <span id="status-badge-{{ $order['id'] }}" class="badge">
                                            {{ ucfirst($order['status']) }}
                                        </span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <form class="status-form" 
                                                  action="{{ route('orders.update-status', $order['id']) }}" 
                                                  method="POST"
                                                  data-order-id="{{ $order['id'] }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="pending">
                                                <button type="submit" class="dropdown-item bg-warning text-white">
                                                    Pending
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('orders.update-status', $order['id']) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="dropdown-item bg-success text-white @if($order['status'] === 'completed') active @endif">
                                                    Completed
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('orders.update-status', $order['id']) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="canceled">
                                                <button type="submit" class="dropdown-item bg-danger text-white @if($order['status'] === 'canceled') active @endif">
                                                    Canceled
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                    
                                </div>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($order['created_at'])
                                   ->setTimezone('Asia/Jakarta')
                                   ->format('d M Y H:i:s') }}
                            </td>
                            <td>
                                <form action="{{ route('orders.destroy', $order['id']) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this order?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No orders found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const orderItemsContainer = document.getElementById('order-items-container');
            let itemCounter = 0;
            
            // Add to cart functionality
            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', function() {
                    const menuId = this.dataset.menuId;
                    const menuName = this.dataset.menuName;
                    const menuPrice = this.dataset.menuPrice;
                    
                    // Create a new item row
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'order-item mb-2 p-2 border';
                    itemDiv.innerHTML = `
                        <input type="hidden" name="items[${itemCounter}][menu_id]" value="${menuId}">
                        <div class="d-flex justify-content-between">
                            <span>${menuName}</span>
                            <span>Rp ${parseInt(menuPrice).toLocaleString('id-ID')}</span>
                        </div>
                        <div class="form-group mt-2">
                            <label>Quantity</label>
                            <input type="number" name="items[${itemCounter}][quantity]" 
                                   class="form-control" value="1" min="1" required>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-item">Remove</button>
                    `;
                    
                    orderItemsContainer.appendChild(itemDiv);
                    itemCounter++;
                    
                    // Add remove functionality
                    itemDiv.querySelector('.remove-item').addEventListener('click', function() {
                        itemDiv.remove();
                    });
                });
            });
        });

        $(document).ready(function() {
            $('#ordersTable').DataTable({
                responsive: true,
                columnDefs: [
                    { responsivePriority: 1, targets: 0 }, // Order ID
                    { responsivePriority: 2, targets: 5 }, // Date
                    { responsivePriority: 3, targets: 3 }, // Total
                    { responsivePriority: 4, targets: 4 }, // Status
                    { responsivePriority: 5, targets: 1 }, // Customer
                    { responsivePriority: 6, targets: 2 }  // Items
                ]
            });
        });

        document.querySelectorAll('.status-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const orderId = this.dataset.orderId;
                const status = this.querySelector('[name="status"]').value;
                const button = this.querySelector('button[type="submit"]');
                const originalText = button.innerHTML;

                // Loading state
                button.innerHTML = `
                    <span class="spinner-border spinner-border-sm" role="status"></span>
                    Updating...
                `;

                // Konfirmasi untuk cancel
                if (status === 'canceled' && !confirm('Yakin ingin membatalkan pesanan?')) {
                    button.innerHTML = originalText;
                    return;
                }

                fetch(this.action, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI
                        const badge = document.querySelector(`#status-badge-${orderId}`);
                        badge.className = `badge bg-${status === 'completed' ? 'success' : status === 'canceled' ? 'danger' : 'warning'}`;
                        badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                        
                        // Close dropdown
                        bootstrap.Dropdown.getInstance(document.querySelector(`#statusDropdown${orderId}`))?.hide();
                    } else {
                        throw new Error(data.error || 'Update failed');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating status: ' + error.message);
                })
                .finally(() => {
                    button.innerHTML = originalText;
                });
            });
        });
    </script>

    
</body>
</html>