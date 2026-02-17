<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function index()
    {
        $todayVisitors     = Visitor::whereDate('visit_date', today())->count();
        $yesterdayVisitors = Visitor::whereDate('visit_date', today()->subDay())->count();
        $allVisitors       = Visitor::count();
        $newVisitors       = Visitor::select('ip_address')->distinct()->count();

        // Growth calculations
        $todayGrowth = $yesterdayVisitors > 0
            ? round((($todayVisitors - $yesterdayVisitors) / $yesterdayVisitors) * 100, 2)
            : ($todayVisitors > 0 ? 100 : 0);

        $previousWeekVisitors = Visitor::whereBetween('visit_date', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek(),
        ])->count();

        $thisWeekVisitors = Visitor::whereBetween('visit_date', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ])->count();

        $weekGrowth = $previousWeekVisitors > 0
            ? round((($thisWeekVisitors - $previousWeekVisitors) / $previousWeekVisitors) * 100, 2)
            : ($thisWeekVisitors > 0 ? 100 : 0);

        $dailyVisitors = Visitor::selectRaw('DATE(visit_date) as date, COUNT(*) as count')
            ->whereBetween('visit_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->groupBy('date')
            ->pluck('count', 'date');
        // This week daily visitors
        $thisWeekVisitorsDaily = Visitor::selectRaw('DATE(visit_date) as date, COUNT(*) as count')
            ->whereBetween('visit_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->groupBy('date')
            ->pluck('count', 'date');

// Previous week daily visitors
        $previousWeekVisitorsDaily = Visitor::selectRaw('DATE(visit_date) as date, COUNT(*) as count')
            ->whereBetween('visit_date', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->groupBy('date')
            ->pluck('count', 'date');

// Yesterday visitors (single value)
        $yesterdayVisitors = Visitor::whereDate('visit_date', today()->subDay())->count();

        return view('admin.index', compact(
            'thisWeekVisitorsDaily',
            'previousWeekVisitorsDaily',
            'yesterdayVisitors',
            'dailyVisitors',
            'todayVisitors',
            'yesterdayVisitors',
            'allVisitors',
            'newVisitors',
            'todayGrowth',
            'previousWeekVisitors',
            'thisWeekVisitors',
            'weekGrowth'
        ));
    }

    public function LoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Welcome to Dashboard!');
        }

        return back()->with('error', 'Invalid email or password.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function cacheclear()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        return redirect()->back()->with('success', 'Cache Cleared successfully!');
    }
}
