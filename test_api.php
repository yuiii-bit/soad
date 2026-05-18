<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    $user = User::firstOrCreate(
        ['email' => 'testapi@example.com'],
        [
            'name' => 'API Tester',
            'password' => Hash::make('password123'),
            'role' => 'user'
        ]
    );

    echo "User created/found: " . $user->email . "\n";
    
    // Test login via HTTP POST (using Guzzle since it's installed by Laravel)
    $client = new \GuzzleHttp\Client(['base_uri' => 'http://127.0.0.1:8000']);
    
    echo "Attempting login...\n";
    $response = $client->post('/api/login', [
        'form_params' => [
            'email' => 'testapi@example.com',
            'password' => 'password123'
        ]
    ]);
    
    $body = json_decode((string) $response->getBody(), true);
    $token = $body['access_token'] ?? null;
    
    if (!$token) {
        throw new \Exception("Failed to get token");
    }
    echo "Login successful. Token: " . substr($token, 0, 10) . "...\n";
    
    // Get a product
    $product = \App\Models\Product::first();
    echo "Placing order for product: " . $product->name . "\n";
    
    $orderResponse = $client->post('/api/orders', [
        'headers' => [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ],
        'json' => [
            'name' => 'API Tester',
            'phone' => '0987654321',
            'address' => '123 API Street',
            'payment_method' => 'COD',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2
                ]
            ]
        ]
    ]);
    
    $orderBody = json_decode((string) $orderResponse->getBody(), true);
    echo "Order Response: " . $orderBody['message'] . " (Status: " . $orderBody['status'] . ")\n";
    echo "Order ID: " . $orderBody['data']['order_id'] . "\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
