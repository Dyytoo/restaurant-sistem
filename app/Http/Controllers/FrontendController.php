<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class FrontendController extends Controller
{

    private $menuServiceUrl = 'http://localhost:8001/api';
    private $orderServiceUrl = 'http://localhost:8002/api';
    private $userServiceUrl = 'http://localhost:8003/api';


    public function index()
    {
        $client = new Client();

        try {
            // Ambil data menu dari MenuService
            $menuResponse = $client->get("http://localhost:8001/api/menus");
            $menus = json_decode($menuResponse->getBody(), true);
        } catch (\Exception $e) {
            \Log::error('Failed to fetch menus: ' . $e->getMessage());
            $menus = [];
        }

        try {
            // Ambil data user dari UserService
            $userResponse = $client->get("http://localhost:8003/api/users");
            $users = json_decode($userResponse->getBody(), true);
        } catch (\Exception $e) {
            \Log::error('Failed to fetch users: ' . $e->getMessage());
            $users = [];
        }

        try {
            // Ambil data order dari OrderService
            $orderResponse = $client->get("http://localhost:8002/api/orders");
            $orders = json_decode($orderResponse->getBody(), true);
        } catch (\Exception $e) {
            \Log::error('Failed to fetch orders: ' . $e->getMessage());
            $orders = [];
        }

        try {
            $orderResponse = $client->get("http://localhost:8002/api/orders?page=1");
            $ordersData = json_decode($orderResponse->getBody(), true);
            $orders = $ordersData['data'];
            $pagination = [
                'current_page' => $ordersData['current_page'],
                'last_page' => $ordersData['last_page']
            ];
        } catch (\Exception $e) {
            // ... error handling
        }

        return view('welcome', compact('menus', 'users', 'orders'));

    }



    public function placeOrder(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'items' => 'required|array',
            'items.*.menu_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        $client = new Client();

        try {
            $response = $client->post("{$this->orderServiceUrl}/orders", [
                'json' => $request->all()
            ]);

            $order = json_decode($response->getBody(), true);
            return redirect()->back()->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to place order: ' . $e->getMessage());
        }
    }

    public function updateOrderStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,canceled'
        ]);

        $client = new Client();

        try {
            $response = $client->patch("http://localhost:8002/api/orders/{$orderId}", [
                'json' => [
                    'status' => $request->status
                ]
            ]);

            return redirect()->back()->with('success', 'Order status updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }

    public function destroyOrder($orderId)
    {
        $client = new Client();

        try {
            $response = $client->delete("http://localhost:8002/api/orders/{$orderId}");

            if ($response->getStatusCode() === 204) {
                return redirect()->back()->with('success', 'Order deleted successfully!');
            }
        } catch (\Exception $e) {
            \Log::error('Failed to delete order: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Order deleted successfully!');
    }
}
