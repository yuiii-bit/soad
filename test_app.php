<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $products = \App\Models\Product::take(5)->get();
    echo "Products count: " . $products->count() . "\n";
    foreach ($products as $p) {
        echo "- " . $p->name . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
