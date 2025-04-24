<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;


class MenuController extends Controller
{

    public function welcome()
    {
        $menus = Menu::all();
        return view('welcome', compact('menus'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Menu::all());
    }

    public function create()
    {
        return view('menus.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|in:makanan,minuman'
        ]);

        try {
            Menu::create($validated);
            return redirect()->route('menus.create')->with('success', 'Menu created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create menu: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json([
                'message' => 'Menu not found',
                'success' => false
            ], 404);
        }

        return response()->json($menu);
    }


    public function getByCategory($category)
    {
        return response()->json(Menu::where('category', $category)->get());
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
            $menu = Menu::findOrFail($id);
            $menu->delete();

            // Cek apakah request dari web atau API
            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Menu deleted successfully!',
                    'id' => $id
                ], 200);
            }

            return redirect()->route('menus.index')->with('success', 'Menu deleted successfully!');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'error' => 'Menu not found with ID: ' . $id
                ], 404);
            }

            return redirect()->back()->with('error', 'Menu not found!');

        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'error' => 'Failed to delete menu: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to delete menu: ' . $e->getMessage());
        }
    }
}
