<?php

namespace App\Providers;

use App\Models\Category;
use App\Services\CartService;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\View;

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
        // $this->configureDefaults();

        // View::composer('partials.navbar', function ($view) {
        //     $mainNames = [
        //         'Beletria', 
        //         'Náučné', 
        //         'Životopisy', 
        //         'Cestovateľské', 
        //         'Kuchárske', 
        //         'Učebnice a slovníky'
        //     ];

        //     $mainCategories = \App\Models\Category::whereIn('name', $mainNames)->get();
        //     $view->with('mainCategories', $mainCategories); 
        // });

        $this->configureDefaults();

        View::composer('partials.navbar', function ($view) {
            // 1. najdeme hlavny koren "Knihy"
            $rootKnihy = \App\Models\Category::where('name', 'Knihy')->first();

            if ($rootKnihy) {
                // 2.vytiahneme vsetky kategorie ktore patria pod nej
                $mainCategories = \App\Models\Category::where('category_id', $rootKnihy->id)->get();
            } else {
                // ak by knihy katehoria nebola
                $mainCategories = collect();
            }

            $view->with('mainCategories', $mainCategories); 
        });

        Event::listen(Login::class, function (Login $event) {
            app(CartService::class)->mergeSessionCartIntoDatabase($event->user);
        });
        
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
