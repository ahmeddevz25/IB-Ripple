<?php
namespace App\Providers;

use App\Models\Page;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        View::composer('index.layout', function ($view) {
            $navPages = Page::where('is_navbar', 1)
                ->whereNull('parent_id')
                ->with([
                    'children' => function ($query) {
                        $query->where('is_navbar', 1)->orderBy('sort_order');
                    }
                ])
                ->orderBy('sort_order')
                ->get();

            $view->with('navPages', $navPages);
        });
    }
}
