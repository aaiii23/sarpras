<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Complaint;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $totalComplaints = Complaint::count();
            $pendingComplaints = Complaint::where('status', 'pending')->count();
            $inProgressComplaints = Complaint::where('status', 'in_progress')->count();
            $resolvedComplaints = Complaint::where('status', 'resolved')->count();
            $rejectedComplaints = Complaint::where('status', 'rejected')->count();

            $totalSiswa = User::where('role', 'siswa')->count();
            $totalAdmin = User::where('role', 'admin')->count();
            $totalCategories = Category::count();
            $totalLocations = Location::count();

            $recentComplaints = Complaint::with(['user', 'category', 'location'])
                ->whereIn('status', ['pending', 'in_progress'])
                ->latest()
                ->take(8)
                ->get();

            // Categories distribution for charts
            $categoriesStats = Category::withCount('complaints')->get();

            return view('dashboard.admin', compact(
                'totalComplaints',
                'pendingComplaints',
                'inProgressComplaints',
                'resolvedComplaints',
                'rejectedComplaints',
                'totalSiswa',
                'totalAdmin',
                'totalCategories',
                'totalLocations',
                'recentComplaints',
                'categoriesStats'
            ));
        }

        // Dashboard Siswa
        $myComplaintsCount = Complaint::where('user_id', $user->id)->count();
        $myPendingCount = Complaint::where('user_id', $user->id)->where('status', 'pending')->count();
        $myInProgressCount = Complaint::where('user_id', $user->id)->where('status', 'in_progress')->count();
        $myResolvedCount = Complaint::where('user_id', $user->id)->where('status', 'resolved')->count();
        $myRejectedCount = Complaint::where('user_id', $user->id)->where('status', 'rejected')->count();

        $recentComplaints = Complaint::with(['category', 'location'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.siswa', compact(
            'myComplaintsCount',
            'myPendingCount',
            'myInProgressCount',
            'myResolvedCount',
            'myRejectedCount',
            'recentComplaints'
        ));
    }
}
