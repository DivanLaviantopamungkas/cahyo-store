<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryController extends BaseAdminController
{
    public function index(Request $request)
    {
        $search = $request->get('q');
        $status = $request->get('status');
        $sort = $request->get('sort', 'order_asc');

        $categories = Category::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($status === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($status === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->when($sort, function ($query) use ($sort) {
                switch ($sort) {
                    case 'name_asc':
                        return $query->orderBy('name');
                    case 'name_desc':
                        return $query->orderByDesc('name');
                    case 'latest':
                        return $query->latest();
                    case 'oldest':
                        return $query->oldest();
                    default: // order_asc
                        return $query->orderBy('order')->orderBy('name');
                }
            })
            ->paginate(15)
            ->appends($request->query());

        return $this->view('categories.index', compact('categories', 'search', 'status', 'sort'));
    }

    public function create()
    {
        // Suggestion untuk order
        $suggestedOrder = Category::max('order') + 1;

        return $this->view('categories.create', compact('suggestedOrder'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,svg', 'max:2048'], // Changed from icon to image
            'is_active'   => ['nullable', 'boolean'],
            'order'       => ['nullable', 'integer', 'min:0'],
        ]);

        // Generate slug
        $slug = Str::slug($data['name']);
        $originalSlug = $slug;
        $counter = 1;

        while (Category::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $data['slug'] = $slug;
        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = $data['order'] ?? Category::max('order') + 1;

        // Handle order jika sudah ada yang sama
        if (Category::where('order', $data['order'])->exists()) {
            Category::where('order', '>=', $data['order'])->increment('order');
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $slug . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('categories', $imageName, 'public');
            $data['image'] = $imagePath;
        }

        Category::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('toast', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Kategori berhasil ditambahkan.'
            ]);
    }

    public function show(Category $category)
    {
        // Ambil produk dalam kategori ini
        $products = $category->products()
            ->withCount(['voucherCodes as total_vouchers', 'voucherCodes as sold_vouchers' => function ($query) {
                $query->where('status', 'sold');
            }])
            ->with('category')
            ->take(10)
            ->get();

        // Statistik real
        $stats = [
            'total_products' => $category->products()->count(),
            'active_products' => $category->products()->where('is_active', true)->count(),
            'total_vouchers' => $category->products()->withCount('voucherCodes')->get()->sum('voucher_codes_count'),
            'sold_this_month' => $category->products()
                ->join('voucher_codes', 'products.id', '=', 'voucher_codes.product_id')
                ->where('voucher_codes.status', 'sold')
                ->whereMonth('voucher_codes.sold_at', now()->month)
                ->count(),
        ];

        return $this->view('categories.show', compact('category', 'products', 'stats'));
    }

    public function edit(Category $category)
    {
        return $this->view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'        => ['sometimes', 'required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
            'icon'        => ['nullable', 'string', 'max:50'],
            'color'       => ['nullable', 'string', 'max:20'],
            'is_active'   => ['nullable', 'boolean'],
            'order'       => ['nullable', 'integer', 'min:0'],
        ]);

        // Generate slug jika nama berubah
        if ($request->has('name') && $request->name != $category->name) {
            $slug = Str::slug($data['name']);
            $originalSlug = $slug;
            $counter = 1;

            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $data['slug'] = $slug;
        }

        $data['is_active'] = $request->boolean('is_active');

        // Handle order change
        if (isset($data['order']) && $data['order'] != $category->order) {
            if ($data['order'] > $category->order) {
                Category::where('order', '>', $category->order)
                    ->where('order', '<=', $data['order'])
                    ->decrement('order');
            } else {
                Category::where('order', '<', $category->order)
                    ->where('order', '>=', $data['order'])
                    ->increment('order');
            }
        }

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('toast', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Kategori berhasil diperbarui.'
            ]);
    }

    public function destroy(Category $category)
    {
        // Cek apakah kategori memiliki produk
        if ($category->products()->exists()) {
            return redirect()
                ->route('admin.categories.index')
                ->with('toast', [
                    'type' => 'error',
                    'title' => 'Gagal!',
                    'message' => 'Tidak dapat menghapus kategori yang masih memiliki produk. Pindahkan atau hapus produk terlebih dahulu.'
                ]);
        }

        $category->delete();

        // Reorder categories
        Category::where('order', '>', $category->order)->decrement('order');

        return redirect()
            ->route('admin.categories.index')
            ->with('toast', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Kategori berhasil dihapus.'
            ]);
    }

    public function toggleStatus(Category $category)
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);

        return back()->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Status kategori berhasil diubah.'
        ]);
    }

    public function orderUp(Category $category)
    {
        $aboveCategory = Category::where('order', '<', $category->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($aboveCategory) {
            // Swap orders
            $currentOrder = $category->order;
            $category->update(['order' => $aboveCategory->order]);
            $aboveCategory->update(['order' => $currentOrder]);
        }

        return back()->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Urutan kategori berhasil diubah.'
        ]);
    }

    public function orderDown(Category $category)
    {
        $belowCategory = Category::where('order', '>', $category->order)
            ->orderBy('order')
            ->first();

        if ($belowCategory) {
            // Swap orders
            $currentOrder = $category->order;
            $category->update(['order' => $belowCategory->order]);
            $belowCategory->update(['order' => $currentOrder]);
        }

        return back()->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Urutan kategori berhasil diubah.'
        ]);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:categories,id'
        ]);

        switch ($request->action) {
            case 'activate':
                Category::whereIn('id', $request->ids)->update(['is_active' => true]);
                $message = 'Kategori berhasil diaktifkan.';
                break;

            case 'deactivate':
                Category::whereIn('id', $request->ids)->update(['is_active' => false]);
                $message = 'Kategori berhasil dinonaktifkan.';
                break;

            case 'delete':
                // Cek apakah ada kategori yang digunakan oleh produk
                $usedCategories = Category::whereIn('id', $request->ids)
                    ->whereHas('products')
                    ->pluck('name');

                if ($usedCategories->isNotEmpty()) {
                    return back()->with('toast', [
                        'type' => 'error',
                        'title' => 'Gagal!',
                        'message' => 'Beberapa kategori tidak dapat dihapus karena masih memiliki produk: ' .
                            $usedCategories->implode(', ')
                    ]);
                }

                Category::whereIn('id', $request->ids)->delete();
                $message = 'Kategori berhasil dihapus.';
                break;
        }

        return back()->with('toast', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => $message
        ]);
    }
}
