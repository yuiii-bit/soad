<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Users
        DB::table('users')->insert([
            [
                'name' => 'Admin Khương',
                'email' => 'admin@crocsvn.vn',
                'password' => Hash::make('password123'),
                'phone' => '0987654321',
                'address' => '123 Đường Crocs, Quận 1, TP.HCM',
                'role' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Khách Hàng VIP',
                'email' => 'khachhang@gmail.com',
                'password' => Hash::make('password123'),
                'phone' => '0901234567',
                'address' => '456 Lê Lợi, Đà Nẵng',
                'role' => 'user',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 2. Categories
        $categories = [
            ['name' => 'Classic Clog', 'slug' => 'classic-clog', 'description' => 'Dòng vọc cổ điển thoải mái nhất.'],
            ['name' => 'Platform', 'slug' => 'platform', 'description' => 'Đế cao tôn dáng hack chiều cao.'],
            ['name' => 'Sandal & Slide', 'slug' => 'sandal-slide', 'description' => 'Dép quai ngang và sandal năng động.'],
            ['name' => 'Trẻ Em (Kids)', 'slug' => 'tre-em', 'description' => 'Crocs siêu nhẹ bảo vệ nhí.'],
        ];
        DB::table('categories')->insert($categories);

        // 3. Brands
        $brands = [
            ['name' => 'Crocs', 'slug' => 'crocs', 'image' => 'logo-crocs.png', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Jibbitz', 'slug' => 'jibbitz', 'image' => 'logo-jibbitz.png', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
        ];
        DB::table('brands')->insert($brands);

        // Lấy ID để insert Products
        $catClassic = DB::table('categories')->where('slug', 'classic-clog')->value('id');
        $catPlatform = DB::table('categories')->where('slug', 'platform')->value('id');
        $catSandal = DB::table('categories')->where('slug', 'sandal-slide')->value('id');
        $brandCrocs = DB::table('brands')->where('slug', 'crocs')->value('id');

        // 4. Products (Dùng hình ảnh bạn đã tạo)
        DB::table('products')->insert([
            [
                'category_id' => $catClassic,
                'brand_id' => $brandCrocs,
                'name' => 'Crocs Classic Clog – Vàng Chanh',
                'slug' => Str::slug('Crocs Classic Clog – Vàng Chanh'),
                'image' => 'images/p1.png',
                'image_list' => json_encode(['images/p1.png']),
                'price' => 750000,
                'discount_price' => 595000,
                'stock' => 50,
                'description' => 'Sản phẩm kinh điển siêu bền nhẹ',
                'content' => 'Trải nghiệm sự thoải mái tuyệt đối với chất liệu Croslite độc quyền. Kháng nước và dễ dàng làm sạch.',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $catClassic,
                'brand_id' => $brandCrocs,
                'name' => 'Crocs Classic Clog – Xanh Navy',
                'slug' => Str::slug('Crocs Classic Clog – Xanh Navy'),
                'image' => 'images/p2.png',
                'image_list' => json_encode(['images/p2.png']),
                'price' => 595000,
                'discount_price' => null,
                'stock' => 100,
                'description' => 'Màu sắc nam tính mạnh mẽ',
                'content' => 'Phù hợp mọi thiết kế hàng ngày.',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $catPlatform,
                'brand_id' => $brandCrocs,
                'name' => 'Crocs Classic Platform – Hồng Pastel',
                'slug' => Str::slug('Crocs Classic Platform – Hồng Pastel'),
                'image' => 'images/p3.png',
                'image_list' => json_encode(['images/p3.png']),
                'price' => 899000,
                'discount_price' => 720000,
                'stock' => 30,
                'description' => 'Đế bánh mì tôn dáng sành điệu',
                'content' => 'Xu hướng thời trang 2024 không thể thiếu với đế cao 4cm hack dáng đỉnh cao.',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $catClassic,
                'brand_id' => $brandCrocs,
                'name' => 'Crocs Classic Clog – Xanh Lá Lime',
                'slug' => Str::slug('Crocs Classic Clog – Xanh Lá Lime'),
                'image' => 'images/p4.png',
                'image_list' => json_encode(['images/p4.png']),
                'price' => 595000,
                'discount_price' => null,
                'stock' => 25,
                'description' => 'Bùng nổ màu sắc mùa hè',
                'content' => 'Thật nổi bật và năng động cùng sắc xanh lá rực rỡ.',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $catClassic,
                'brand_id' => $brandCrocs,
                'name' => 'Crocs Classic Clog – Trắng Tinh Khôi',
                'slug' => Str::slug('Crocs Classic Clog – Trắng Tinh Khôi'),
                'image' => 'images/p5.png',
                'image_list' => json_encode(['images/p5.png']),
                'price' => 595000,
                'discount_price' => null,
                'stock' => 200,
                'description' => 'Sạch sẽ, thanh lịch, best seller',
                'content' => 'Dễ dàng mix & match Jibbitz tạo nên phong cách độc bản cho riêng bạn.',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'category_id' => $catSandal,
                'brand_id' => $brandCrocs,
                'name' => 'Crocs Baya Sandal – Cam Coral',
                'slug' => Str::slug('Crocs Baya Sandal – Cam Coral'),
                'image' => 'images/p6.png',
                'image_list' => json_encode(['images/p6.png']),
                'price' => 560000,
                'discount_price' => 480000,
                'stock' => 40,
                'description' => 'Sandal đi biển thoáng mát',
                'content' => 'Quai vắt ngang êm ái, đảm bảo bước đi vững chãi nhưng không gò bó.',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // 5. Coupons
        DB::table('coupons')->insert([
            ['code' => 'CROCS10', 'discount_value' => 10, 'min_order_value' => 500000, 'quantity' => 100, 'expired_at' => Carbon::now()->addDays(30)],
            ['code' => 'FREESHIP50K', 'discount_value' => 50000, 'min_order_value' => 0, 'quantity' => 50, 'expired_at' => Carbon::now()->addDays(15)],
            ['code' => 'HELLO2025', 'discount_value' => 25, 'min_order_value' => 1000000, 'quantity' => 10, 'expired_at' => Carbon::now()->addDays(5)],
        ]);

        // 6. Banners
        DB::table('banners')->insert([
             ['title' => 'Sale mùa hè', 'image' => 'images/hero.png', 'link' => '#', 'position' => 'hero', 'status' => 1]
        ]);
        
        $adminId = DB::table('users')->where('email', 'admin@crocsvn.vn')->value('id');
        $userId = DB::table('users')->where('email', 'khachhang@gmail.com')->value('id');
        $p1 = DB::table('products')->where('slug', 'crocs-classic-clog-vang-chanh')->value('id');
        $p2 = DB::table('products')->where('slug', 'crocs-classic-platform-hong-pastel')->value('id');

        // 7. Orders & Order Details
        $orderId = DB::table('orders')->insertGetId([
             'user_id' => $userId,
             'order_code' => 'ORD-12345',
             'total_amount' => 1315000,
             'status' => 'completed',
             'shipping_address' => '456 Lê Lợi, Đà Nẵng',
             'payment_method' => 'cod',
             'created_at' => clone $now->subDays(2),
             'updated_at' => clone $now->subDays(1),
        ]);

        DB::table('order_details')->insert([
             ['order_id' => $orderId, 'product_id' => $p1, 'quantity' => 1, 'price' => 595000, 'created_at' => $now, 'updated_at' => $now],
             ['order_id' => $orderId, 'product_id' => $p2, 'quantity' => 1, 'price' => 720000, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // 8. Payments
        DB::table('payments')->insert([
             'order_id' => $orderId,
             'payment_method' => 'cod',
             'status' => 'paid',
        ]);

        // 9. Reviews
        DB::table('reviews')->insert([
            ['user_id' => $userId, 'product_id' => $p1, 'rating' => 5, 'comment' => 'Dép vàng đẹp xỉu, mang êm chân cực kì, rất ưng nha shop ơi!', 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => $userId, 'product_id' => $p2, 'rating' => 4, 'comment' => 'Form platform lên dáng chuẩn đét', 'created_at' => $now, 'updated_at' => $now],
        ]);
        
        // 10. Carts (Gio hang chua dat)
        DB::table('carts')->insert([
             ['user_id' => $userId, 'product_id' => $p1, 'quantity' => 2, 'created_at' => $now, 'updated_at' => $now]
        ]);

        // 11. Wishlists
        DB::table('wishlists')->insert([
             ['user_id' => $userId, 'product_id' => $p2, 'created_at' => $now]
        ]);
    }
}
