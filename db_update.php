<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    if (!Schema::hasColumn('products', 'flash_sale_end')) {
        Schema::table('products', function (Blueprint $table) {
            $table->dateTime('flash_sale_end')->nullable()->after('discount_price');
        });
        echo "Column created successfully.\n";
    } else {
        echo "Column already exists.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
