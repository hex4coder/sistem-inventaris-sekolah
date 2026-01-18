<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Only gather data if the user is an admin
        if (Auth::user()->isAdmin()) {
            // Trend Data: Borrowings per day for the last 30 days
            $borrowingTrends = Borrowing::select(
                DB::raw('DATE(borrow_date) as date'),
                DB::raw('count(*) as count')
            )
                ->where('borrow_date', '>=', now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Status Distribution Data
            $borrowingStats = Borrowing::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();

            // Ensure all statuses are present even if count is 0
            $statuses = ['pending', 'approved', 'returned', 'rejected'];
            $statusCounts = [];
            foreach ($statuses as $status) {
                $statusCounts[$status] = $borrowingStats[$status] ?? 0;
            }

            return view('dashboard', compact('borrowingTrends', 'statusCounts'));
        }

        // For non-admins, just show the view without special data (or handle differently)
        return view('dashboard');
    }
}
