<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_sales'    => Order::where('status', 'completed')->sum('total_amount'),
            'total_orders'   => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'total_users'    => User::where('role', 'user')->count(),
        ];

        $recent_orders = Order::with('user')->orderBy('created_at', 'desc')->take(8)->get();

        // Doanh thu 6 tháng gần nhất
        $monthlyData = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('EXTRACT(MONTH FROM created_at)::integer as month'),
                DB::raw('EXTRACT(YEAR FROM created_at)::integer as year'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy(DB::raw('EXTRACT(YEAR FROM created_at)'), DB::raw('EXTRACT(MONTH FROM created_at)'))
            ->orderBy(DB::raw('EXTRACT(YEAR FROM created_at)'))
            ->orderBy(DB::raw('EXTRACT(MONTH FROM created_at)'))
            ->get();

        $monthly_labels   = [];
        $monthly_revenues = [];
        // Tạo 6 tháng
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $label = 'T' . $date->format('n/y');
            $monthly_labels[] = $label;
            $found = $monthlyData->first(fn($r) => $r->month == $date->month && $r->year == $date->year);
            $monthly_revenues[] = $found ? (int)$found->revenue : 0;
        }

        // Top sản phẩm bán chạy
        $top_products = Product::select('products.*', DB::raw('SUM(order_details.quantity) as sold'))
            ->join('order_details', 'products.id', '=', 'order_details.product_id')
            ->groupBy('products.id')
            ->orderByDesc('sold')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'recent_orders',
            'monthly_labels', 'monthly_revenues',
            'top_products'
        ));
    }
}
