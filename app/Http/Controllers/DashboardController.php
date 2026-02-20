<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        $user = Auth::user();

        if ($user->role === 'customer') {
            $totalUsers = 1;
            $totalCustomers = 1; 
            $totalInvoices = \App\Models\Invoice::whereHas('customer', function($q) use ($user) {
                $q->where('user_id', $user->user_id);
            })->count();
        } else {
            $totalUsers = \App\Models\User::count();
            $totalCustomers = \App\Models\Customer::count();
            $totalInvoices = \App\Models\Invoice::count();
        }

        return view('admin.dashboard', compact('totalUsers', 'totalCustomers', 'totalInvoices'));
    }
}
