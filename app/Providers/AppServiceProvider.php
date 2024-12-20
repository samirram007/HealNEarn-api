<?php

namespace App\Providers;

use App\Services\IAuthService;
use App\Services\IDocumentService;
use App\Services\IManagerService;
use App\Services\IMemberService;
use App\Services\impl\AuthService;
use App\Services\impl\DocumentService;
use App\Services\impl\ManagerService;
use App\Services\impl\MemberService;
use App\Services\impl\ProductService;
use App\Services\impl\UserService;
use App\Services\IProductService;
use App\Services\IUserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IDocumentService::class, DocumentService::class);
        $this->app->bind(IProductService::class, ProductService::class);
        $this->app->bind(IMemberService::class, MemberService::class);
        $this->app->bind(IManagerService::class, ManagerService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
