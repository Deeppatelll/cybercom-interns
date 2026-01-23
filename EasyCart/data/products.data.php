<?php
$products = array(
  array(
    'id' => 1,
    'name' => 'Fresh Apples',
    'price' => 120,
    'quantity' => '1 kg',
    'image' => 'images/apple.jpg',
    'category' => 'Fruits',
    'brand' => 'Green Valley',
    'unit' => 'kg',
    'weight_value' => 1,
    'description' => 'These fresh, crispy apples are handpicked from premium orchards. Loaded with natural vitamins and fiber, they\'re perfect for a healthy breakfast or as a snack. Rich in antioxidants, these apples help boost your immune system and are great for overall wellness.'
  ),
  array(
    'id' => 2,
    'name' => 'Yellow Bananas',
    'price' => 60,
    'quantity' => '6 pcs',
    'image' => 'images/banana.jpg',
    'category' => 'Fruits',
    'brand' => 'Fresh Harvest',
    'unit' => 'pcs',
    'weight_value' => 6,
    'description' => 'Ripe and sweet yellow bananas packed with potassium and natural energy. Perfect for snacking or smoothies.'
  ),
  array(
    'id' => 3,
    'name' => 'Fresh Milk',
    'price' => 65,
    'quantity' => '1 Liter',
    'image' => 'images/milk.jpg',
    'category' => 'Dairy',
    'brand' => 'Pure Dairy',
    'unit' => 'liter',
    'weight_value' => 1,
    'description' => 'Pure, fresh dairy milk delivered daily. Rich in calcium and proteins for your family\'s health.'
  ),
  array(
    'id' => 4,
    'name' => 'Whole Wheat Bread',
    'price' => 35,
    'quantity' => '400g',
    'image' => 'images/bread.jpg',
    'category' => 'Bakery',
    'brand' => 'Baker\'s Pride',
    'unit' => 'pcs',
    'weight_value' => 1,
    'description' => 'Fresh whole wheat bread baked with natural ingredients. High in fiber and nutrients.'
  ),
  array(
    'id' => 5,
    'name' => 'Basmati Rice',
    'price' => 450,
    'quantity' => '5 kg',
    'image' => 'images/rice.jpg',
    'category' => 'Staples',
    'brand' => 'Rajesh',
    'unit' => 'kg',
    'weight_value' => 5,
    'description' => 'Premium quality basmati rice with perfect grain separation and aroma.'
  ),
  array(
    'id' => 6,
    'name' => 'Cooking Oil',
    'price' => 210,
    'quantity' => '1 Liter',
    'image' => 'images/oil.jpg',
    'category' => 'Staples',
    'brand' => 'Gold Standard',
    'unit' => 'liter',
    'weight_value' => 1,
    'description' => 'Pure, refined cooking oil perfect for everyday cooking needs.'
  ),
  array(
    'id' => 7,
    'name' => 'Brown Eggs',
    'price' => 72,
    'quantity' => '12 pcs',
    'image' => 'images/eggs.jpg',
    'category' => 'Dairy',
    'brand' => 'Farm Fresh',
    'unit' => 'pcs',
    'weight_value' => 12,
    'description' => 'Fresh brown eggs from farm. Rich in nutrients and protein.'
  ),
  array(
    'id' => 8,
    'name' => 'Fresh Onions',
    'price' => 40,
    'quantity' => '1 kg',
    'image' => 'images/onion.jpg',
    'category' => 'Vegetables',
    'brand' => 'Green Valley',
    'unit' => 'kg',
    'weight_value' => 1,
    'description' => 'Fresh, crispy onions ideal for all your culinary needs.'
  ),
  array(
    'id' => 9,
    'name' => 'Ripe Tomatoes',
    'price' => 50,
    'quantity' => '1 kg',
    'image' => 'images/tomato.jpg',
    'category' => 'Vegetables',
    'brand' => 'Fresh Harvest',
    'unit' => 'kg',
    'weight_value' => 1,
    'description' => 'Farm-fresh ripe tomatoes perfect for cooking or salads.'
  ),
  array(
    'id' => 10,
    'name' => 'Potato Chips',
    'price' => 45,
    'quantity' => '200g',
    'image' => 'images/chips.jpg',
    'category' => 'Snacks',
    'brand' => 'Crispy Bites',
    'unit' => 'pcs',
    'weight_value' => 1,
    'description' => 'Crispy potato chips, a perfect snack for any time.'
  )
);


function getProductById(int $id, array $products)
{
    foreach ($products as $product) {
        if ($product['id'] === $id) {
            return $product;
        }
    }
    return null;
}

?>