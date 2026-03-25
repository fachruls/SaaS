<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashierShift;
use App\Models\Product;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $store = auth()->user()->store;

        $todaySales = Transaction::completed()
            ->today()
            ->sum('total');

        $todayTransactions = Transaction::completed()
            ->today()
            ->count();

        $totalProducts = Product::active()->count();

        $lowStockProducts = Product::active()
            ->where('stock', '<=', 5)
            ->count();

        $activeShift = CashierShift::open()
            ->latest()
            ->first();

        $recentTransactions = Transaction::with('user')
            ->completed()
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'store',
            'todaySales',
            'todayTransactions',
            'totalProducts',
            'lowStockProducts',
            'activeShift',
            'recentTransactions'
        ));
    }
}
