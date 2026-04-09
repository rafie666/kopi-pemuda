<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        $menus = $query->get();
        
        $topMenuIds = \App\Models\DetailTransaksi::select('menu_id', \Illuminate\Support\Facades\DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('menu_id')
            ->orderByDesc('total_sold')
            ->take(3)
            ->pluck('menu_id')
            ->toArray();

        return view('admin.menu.index', compact('menus', 'topMenuIds'));
    }

    public function create()
    {
        return view('admin.menu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'category' => 'required',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menus', 'public');
            $data['image'] = $imagePath;
        }

        $data['is_best_seller'] = $request->has('is_best_seller');

        Menu::create($data);

        $this->logActivity('telah menambahkan produk baru: ' . $request->name);

        return redirect()->back()->with('success', 'Menu baru berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        return view('admin.menu.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'category' => 'required',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $imagePath = $request->file('image')->store('menus', 'public');
            $data['image'] = $imagePath;
        }

        $data['is_best_seller'] = $request->has('is_best_seller');

        $menu->update($data);

        $this->logActivity('telah memperbarui produk: ' . $request->name);

        return redirect()->back()->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }
        $menuName = $menu->name;
        $menu->delete();
        
        $this->logActivity('telah menghapus produk: ' . $menuName);
        
        return redirect()->back()->with('success', 'Menu berhasil dihapus.');
    }

    public function quickStock(Request $request, Menu $menu)
    {
        $request->validate([
            'change' => 'nullable|integer',
            'stock' => 'nullable|integer|min:0'
        ]);

        if ($request->has('stock')) {
            $newStock = $request->stock;
        } else {
            $newStock = $menu->stock + ($request->change ?? 0);
        }

        if ($newStock < 0) {
            return response()->json(['success' => false, 'message' => 'Stok tidak bisa negatif'], 422);
        }

        $menu->update(['stock' => $newStock]);

        return response()->json([
            'success' => true,
            'new_stock' => $menu->stock
        ]);
    }

    public function toggleStatus(Request $request, Menu $menu)
    {
        $request->validate([
            'is_available' => 'required|boolean'
        ]);

        $menu->update(['is_available' => $request->is_available]);

        $status = $request->is_available ? 'Tersedia' : 'Habis';
        $this->logActivity("mengubah ketersediaan produk {$menu->name} menjadi: {$status}");

        return response()->json([
            'success' => true,
            'is_available' => $menu->is_available
        ]);
    }
}
