<?php

namespace App\Http\Controllers;

use App\Mail\NewOrderMail;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Builder;
use App\Models\SiteSetting;

class StoreController extends Controller
{
    public function home(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $products = Product::where('is_active', true)
            ->when($search, fn(Builder $query) => $query->where(fn(Builder $query) => $query
                ->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')))
            ->latest()->get();

        return view('welcome', ['products' => $products, 'search' => $search, 'siteSettings' => SiteSetting::first()]);
    }

    public function addToCart(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        $cart[$product->id] = min(99, ($cart[$product->id] ?? 0) + max(1, (int) $request->input('quantity', 1)));
        $request->session()->put('cart', $cart);

        return back()->with('success', $product->name . ' added to your cart.');
    }

    public function buyNow(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        $cart[$product->id] = min(99, ($cart[$product->id] ?? 0) + 1);
        $request->session()->put('cart', $cart);

        return to_route('cart')->with('success', $product->name . ' added to your cart.');
    }

    public function cart(Request $request): View
    {
        $cart = $request->session()->get('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $subtotal = collect($cart)->sum(fn($quantity, $id) => $products->get($id)?->price * $quantity);
        $deliveryCharge = (float) (SiteSetting::first()?->delivery_charge ?? 0);
        $total = $subtotal + $deliveryCharge;

        return view('cart', compact('cart', 'products', 'subtotal', 'deliveryCharge', 'total'));
    }

    public function updateCart(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'quantities' => ['array'],
            'quantities.*' => ['integer', 'min:0', 'max:99'],
            'remove_product' => ['nullable', 'integer'],
        ]);
        $cart = collect($validated['quantities'] ?? [])->filter(fn($quantity) => $quantity > 0)->all();

        if ($validated['remove_product'] ?? null) {
            unset($cart[$validated['remove_product']]);
        }

        $request->session()->put('cart', $cart);

        return to_route('cart')->with('success', ($validated['remove_product'] ?? null) ? 'Product removed from your cart.' : 'Cart updated.');
    }

    // public function checkout(Request $request): View|RedirectResponse
    // {
    //     if (empty($request->session()->get('cart', []))) {
    //         return to_route('home')->with('error', 'Add an item before checking out.');
    //     }

    //     return view('checkout', ['deliveryCharge' => (float) (SiteSetting::first()?->delivery_charge ?? 0)]);
    // }

    public function checkout(Request $request): View|RedirectResponse
    {
        $placedOrderId = $request->session()->get('placed_order_id');
        $placedOrder   = $placedOrderId ? Order::with('items')->find($placedOrderId) : null;

        // If a just-placed order exists, show the success view.
        if ($placedOrder) {
            return view('checkout', [
                'placedOrder'    => $placedOrder,
                'cart'           => [],
                'products'       => collect(),
                'subtotal'       => 0,
                'deliveryCharge' => 0,
                'total'          => $placedOrder->total,
            ]);
        }

        // Otherwise normal checkout flow
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return to_route('home')->with('error', 'Add an item before checking out.');
        }

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $subtotal = collect($cart)->sum(
            fn($qty, $id) => ($products->get($id)?->price ?? 0) * $qty
        );
        $deliveryCharge = (float) (SiteSetting::first()?->delivery_charge ?? 0);
        $total = $subtotal + $deliveryCharge;

        return view('checkout', compact('cart', 'products', 'subtotal', 'deliveryCharge', 'total'));
    }

    public function placeOrder(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'address'       => ['required', 'string', 'max:500'],
            'mobile'        => ['required', 'string', 'max:30'],
        ]);

        $cart = $request->session()->get('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        abort_if($products->isEmpty(), 422, 'Your cart is empty.');

        $deliveryCharge = (float) (SiteSetting::first()?->delivery_charge ?? 0);

        $order = DB::transaction(function () use ($data, $cart, $products, $deliveryCharge): Order {
            $items = [];
            $subtotal = 0;
            foreach ($cart as $productId => $quantity) {
                $product = $products->get($productId);
                if (! $product) continue;
                $subtotal += $product->price * $quantity;
                $items[] = [
                    'product_id'   => $product->id,
                    'product_name' => $product->name,
                    'price'        => $product->price,
                    'quantity'     => $quantity,
                ];
            }
            $order = Order::create([
                ...$data,
                'user_id' => auth()->id(),
                'total'   => $subtotal + $deliveryCharge,
            ]);
            $order->items()->createMany($items);
            return $order;
        });

        // Clear the cart
        $request->session()->forget('cart');

        // Flash the placed order so the checkout page knows to show the success state
        $request->session()->flash('placed_order_id', $order->id);

        $adminEmail = config('mail.from.address');
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new NewOrderMail($order->load('items')));
        }

        return to_route('checkout')
            ->with('success', 'Order #' . $order->id . ' placed successfully. You can edit the details and submit another order.');
    }
}
