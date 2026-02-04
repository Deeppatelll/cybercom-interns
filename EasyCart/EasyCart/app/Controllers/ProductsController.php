<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\BrandRepository;
use App\Models\CartService;
use App\Models\CategoryRepository;
use App\Models\ProductRepository;

class ProductsController extends Controller
{
    public function index(): void
    {
        $cart_count = CartService::getCartCount($_SESSION['cart'] ?? []);

        $search_keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

        $all_categories = CategoryRepository::all();
        $all_brands = BrandRepository::all();
        $all_units = ProductRepository::getDistinctUnits();

        $selected_categories = isset($_GET['category'])
            ? (is_array($_GET['category']) ? $_GET['category'] : array($_GET['category']))
            : array();
        $selected_brands = isset($_GET['brand'])
            ? (is_array($_GET['brand']) ? $_GET['brand'] : array($_GET['brand']))
            : array();
        $selected_price = isset($_GET['price']) ? $_GET['price'] : '';
        $selected_units = isset($_GET['unit'])
            ? (is_array($_GET['unit']) ? $_GET['unit'] : array($_GET['unit']))
            : array();

        $selected_categories = array_values(array_filter(array_map('trim', $selected_categories)));
        $selected_brands = array_values(array_filter(array_map('trim', $selected_brands)));
        $selected_units = array_values(array_filter(array_map('trim', $selected_units)));

        $per_page = 5;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $filters = [
            'search' => $search_keyword,
            'categories' => $selected_categories,
            'brands' => $selected_brands,
            'price' => $selected_price,
            'units' => $selected_units,
        ];

        $results = ProductRepository::getFilteredProducts($filters, $page, $per_page);
        $paginated_products = $results['items'];
        $total_products = $results['total'];
        $total_pages = (int)ceil($total_products / $per_page);
        $filtered_products = $paginated_products;

        $this->render('products', [
            'filtered_products' => $filtered_products,
            'paginated_products' => $paginated_products,
            'search_keyword' => $search_keyword,
            'all_categories' => $all_categories,
            'all_brands' => $all_brands,
            'all_units' => $all_units,
            'selected_categories' => $selected_categories,
            'selected_brands' => $selected_brands,
            'selected_price' => $selected_price,
            'selected_units' => $selected_units,
            'page' => $page,
            'per_page' => $per_page,
            'total_products' => $total_products,
            'total_pages' => $total_pages,
            'cart_count' => $cart_count,
            'active_page' => 'products',
            'page_title' => 'Products - EasyCart'
        ]);
    }
}
