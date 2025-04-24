<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

    public function welcome()
    {
        $users = User::all();
        return view('welcome', compact('users'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::all());
    }

    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|regex:/^[0-9]+$/|min:10|max:20',
                'address' => 'required|string'
            ]);

            $user = User::create($validated);

            // Jika request-nya ingin JSON (Postman / AJAX)
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'User created successfully!',
                    'user' => $user
                ], 201);
            }

            // Jika dari browser (form biasa)
            return redirect()->route('users.create')->with('success', 'User created successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Failed to create user',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()->withInput()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return response()->json(User::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
    
            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'User deleted successfully!',
                    'id' => $id
                ], 200);
            }
    
            return redirect()->route('user.dashboard')->with('success', 'User deleted successfully!');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'error' => 'User not found with ID: ' . $id
                ], 404);
            }

            return redirect()->back()->with('error', 'User not found!');

        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'error' => 'Failed to delete user: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }
}
