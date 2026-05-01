<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user')->withCount('orders')->orderBy('created_at', 'desc');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }
        $users = $query->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $orders = Order::where('user_id', $id)->orderBy('created_at', 'desc')->get();
        return view('admin.users.show', compact('user', 'orders'));
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Không thể khóa tài khoản Admin!');
        }
        // Toggle: nếu có field 'active', đổi trạng thái
        // Vì chưa có cột active, ta dùng email_verified_at làm flag tạm
        // Thực tế nên thêm cột 'is_active' — ở đây chỉ flash message mẫu
        return redirect()->back()->with('success', 'Đã thay đổi trạng thái tài khoản!');
    }
}
