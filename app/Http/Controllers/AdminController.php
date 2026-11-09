<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function login(Request $request): View|RedirectResponse
    {
        if ($request->session()->get('is_admin', false)) {
            return to_route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        if (
            !hash_equals((string) env('ADMIN_EMAIL', 'test@gmail.com'), (string) $credentials['email'])
            || !hash_equals((string) env('ADMIN_PASSWORD', '12345678'), (string) $credentials['password'])
        ) {
            return back()->withErrors(['email' => 'Those admin login details are not correct.'])->onlyInput('email');
        }
        $request->session()->regenerate();
        $request->session()->put('is_admin', true);
        return to_route('admin.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('is_admin');
        $request->session()->regenerateToken();
        return to_route('admin.login');
    }

    public function dashboard(): View
    {
        $monthlySales = collect(range(5, 0))->map(function (int $monthsAgo): array {
            $date = now()->subMonths($monthsAgo);
            return ['label' => $date->format('M'), 'total' => (float) Order::where('status', 'confirmed')->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->sum('total')];
        });

        return view('admin.dashboard', [
            'orderCount' => Order::count(),
            'pendingCount' => Order::where('status', 'new')->count(),
            'confirmedSales' => Order::where('status', 'confirmed')->sum('total'),
            'monthlySales' => $monthlySales,
        ]);
    }

    public function orders(string $status): View
    {
        abort_unless(in_array($status, ['new', 'shipped', 'confirmed', 'cancelled'], true), 404);

        return view('admin.orders', [
            'orders' => Order::with('items')->where('status', $status)->latest()->get(),
            'status' => $status,
            'title' => match ($status) {
                'new' => 'New orders',
                'shipped' => 'Shipping orders',
                'confirmed' => 'Confirmed orders',
                'cancelled' => 'Cancelled orders',
            },
        ]);
    }

    public function products(): View
    {
        return view('admin.products', ['products' => Product::latest()->get(), 'siteSettings' => SiteSetting::first()]);
    }

    public function updateSiteSettings(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_name' => ['nullable','string', 'max:120'],
            'hero_title' => ['nullable','string', 'max:180'],
            'hero_description' => ['nullable','string', 'max:1000'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'delivery_charge' => ['nullable','numeric', 'min:0', 'max:99999999.99'],
        ]);
        $settings = SiteSetting::firstOrCreate([]);
        
      // Handle image upload (unchanged)
    if ($request->hasFile('hero_image')) {
        $this->deleteStoredImage($settings->hero_image);
        $data['hero_image'] = $request->file('hero_image')->store('site', 'public');
    } else {
        unset($data['hero_image']);
    }
        // Only update text fields the user actually filled in.
        // Empty string → skip (keep the old DB value).
        foreach (['company_name', 'hero_title', 'hero_description'] as $field) {
            if (! $request->filled($field)) {
                unset($data[$field]);
            }
        }
        $settings->update($data);

        return back()->with('success', 'Home page settings updated.');
    }

    public function updateOrderStatus(Request $request, Order $order): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', 'in:new,confirmed,shipped,cancelled']]);
        $order->update($data);

        return back()->with('success', 'Order #' . $order->id . ' marked as ' . $data['status'] . '.');
    }

    public function storeProduct(Request $request): RedirectResponse
    {
        Product::create($this->validateProduct($request));
        return back()->with('success', 'Product added.');
    }

    public function updateProduct(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validateProduct($request);
        if ($request->hasFile('image')) {
            $this->deleteStoredImage($product->image_url);
        }
        $product->update($data);
        return back()->with('success', 'Product updated.');
    }

    public function deleteProduct(Product $product): RedirectResponse
    {
        $this->deleteStoredImage($product->image_url);
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }

    private function validateProduct(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'description' => ['required', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ]) + ($request->hasFile('image')
            ? ['image_url' => $request->file('image')->store('products', 'public')]
            : []);
    }

    private function deleteStoredImage(?string $path): void
    {
        if ($path && !filter_var($path, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($path);
        }
    }
}
