<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Validation\Rule;


class OrderController extends Controller
{
    private $menuServiceUrl = 'http://localhost:8001/api';
    private $userServiceUrl = 'http://localhost:8003/api';
    /**
     * Display a listing of the resource.
     */

    public function welcome()
    {
        $orders = Order::latest()->take(5)->get();
        return view('welcome', compact('orders'));
    }
    public function index()
    {
        $orders = Order::with('items')->paginate(10);
        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi awal request
        $request->validate([
            'user_id' => 'required|integer',
            'items' => 'required|array',
            'items.*.menu_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        $client = new Client();

        try {
            // Cek apakah user valid
            $userResponse = $client->get("{$this->userServiceUrl}/users/{$request->user_id}");
            $user = json_decode($userResponse->getBody(), true);
        } catch (\Exception $e) {
            return response()->json(['error' => 'User not found or user service error'], 404);
        }

        // Proses menu dan hitung total
        $totalAmount = 0;
        $items = [];

        foreach ($request->items as $item) {
            try {
                $menuResponse = $client->get("{$this->menuServiceUrl}/menus/{$item['menu_id']}");
                $menu = json_decode($menuResponse->getBody(), true);
            } catch (\Exception $e) {
                return response()->json(['error' => "Menu with ID {$item['menu_id']} not found or menu service error"], 404);
            }

            $subtotal = $menu['price'] * $item['quantity'];
            $totalAmount += $subtotal;

            $items[] = [
                'menu_id' => $menu['id'],
                'quantity' => $item['quantity'],
                'price' => $menu['price']
            ];
        }

        // Buat order
        $order = Order::create([
            'user_id' => $user['id'],
            'total_amount' => $totalAmount
        ]);

        // Buat order items
        foreach ($items as $item) {
            $order->items()->create($item);
        }

        return response()->json($order->load('items'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,canceled'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return response()->json($order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->delete();

            return response()->json(['message' => 'Order deleted successfully.'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Order not found.'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete order: ' . $e->getMessage()], 500);
        }
    }
}
