<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ProductRepository;
use App\Models\CategoryRepository;
use App\Models\BrandRepository;
use App\Models\CartService;


class HomeController extends Controller
{
    public function index(): void
    {
        $products = ProductRepository::all();
        $featured_products = array_slice($products, 0, 3);

        $home_categories = array_slice(CategoryRepository::all(), 0, 4);
        $home_brands = array_slice(BrandRepository::all(), 0, 6);

        $cart_count = CartService::getCartCount($_SESSION['cart'] ?? []);

        $this->render('home', [
            'featured_products' => $featured_products,
            'home_categories' => $home_categories,
            'home_brands' => $home_brands,
            'cart_count' => $cart_count,
            'active_page' => 'home',
            'page_title' => 'EasyCart - Fast Grocery Delivery'
        ]);
    }
}
