<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    //controlerr index
    public function index()
    {
        $categories = Category::all();
        $latest_product = Product::latest()->take(4)->get();
        $random_product = Product::inRandomOrder()->take(4)->get();
        return view('front.index', compact('categories', 'latest_product', 'random_product'));
    }
    public function category(Category $category)
    {
        session()->put('category_id', $category->id);
        return view('front.brands', compact('category'));
    }
    public function brand(Brand $brand)
    {
        $category_id = session()->get('category_id');
        $products = Product::where('brand_id', $brand->id)
            ->where('category_id', $category_id)
            ->latest()
            ->get();

        return view('front.gadgets', compact('brand', 'products'));

    }

    public function details(Product $product)
    {
        return view('front.details', compact('product'));
    }
    public function booking(Product $product)
    {
        $stores = Store::all();
        return view('front.booking', compact('product', 'stores'));
    }

    public function booking_save(StoreBookingRequest $request, Product $product)
    {
        $bookingData = $request->only(['duration', 'started_at', 'store_id', 'delivery_type', 'address']);
        session(['bookingData' => $bookingData]);

        return redirect()->route('front.checkout', $product->slug);
    }

    public function checkout(Product $product){
        $duration = session('duration');
        dd($duration);
    }

}