<?php

use App\Http\Controllers\WebsiteController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::middleware('set-last-online')->group(function () {
    Route::controller(WebsiteController::class)->group(function (){
        Route::get('/', 'index')->name('home');
        Route::get('/shop', 'shop')->name('shop');
        Route::get('/shop/{category}', 'shopPerCategory')->name('shop.per-category');
        Route::get('/about', 'about')->name('about');
        Route::get('/blog', 'blog')->name('blog');
        Route::get('/single-blog/{id}', 'sblog')->name('sblog');
        Route::get('/product/{id}', 'show')->name('product.show');
        Route::get('/rating/{product_id}', 'rating')->name('rating.index');
    });

    Route::middleware('auth')->group(function () {
        Route::middleware('role:Admin')->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

            Route::controller(\App\Http\Controllers\ProductController::class)->name('product.')->prefix('product')->group(function (){
                Route::get('/', 'index')->name('index');
            });
            
            Route::controller(\App\Http\Controllers\MessageController::class)->name('admin.message.')->prefix('admin/message')->group(function (){
                Route::get('/{user_id}', 'index')->name('index');
                Route::get('/', 'list')->name('list');
            });
            
            Route::controller(\App\Http\Controllers\OrderController::class)->name('order.')->prefix('order')->group(function (){
                Route::get('/', 'index')->name('index');
                Route::get('/invoice/{id}', 'invoice')->name('invoice');
                Route::get('/details/{id}', 'detailsShow')->name('details.show');
            });

            Route::controller(\App\Http\Controllers\CategoryController::class)->name('category.')->prefix('category')->group(function (){
                Route::get('/', 'index')->name('index');
            });

            Route::controller(\App\Http\Controllers\BudgetController::class)->name('budget.')->prefix('budget')->group(function (){
                Route::get('/', 'index')->name('index');
                Route::get('/expense', 'expense')->name('expense');
                Route::get('/report', 'report')->name('report');
                Route::get('/expense-report', 'expenseReport')->name('expense-report');
            });

            Route::get('/log', [\App\Http\Controllers\LogController::class, 'index'])->name('log');
            
            Route::get('/rabbit/breeding', [\App\Http\Controllers\BreedingController::class, 'index'])->name('rabbit.breeding');

            Route::get('/rabbit/illness', [\App\Http\Controllers\IllnessController::class, 'index'])->name('rabbit.illness');
            
            Route::controller(\App\Http\Controllers\RabbitController::class)->name('rabbit.')->prefix('rabbit')->group(function (){
                Route::get('/', 'index')->name('index');
                Route::get('/breed', 'breed')->name('breed');
                Route::get('/weight', 'weight')->name('weight');
            });

            Route::controller(\App\Http\Controllers\BlogController::class)->name('blog.')->prefix('blog')->group(function (){
                Route::get('/index', 'index')->name('index');
                Route::get('/show/{id}', 'show')->name('show');
            });

            Route::controller(\App\Http\Controllers\TaskController::class)->name('task.')->prefix('task')->group(function (){
                Route::get('/', 'index')->name('index');
                Route::get('/completed', 'completed')->name('completed');
            });
        });
        
        Route::controller(WebsiteController::class)->middleware(['role:User'])->group(function (){
            // Route::controller(WebsiteController::class)->middleware(['mobile.verified', 'role:User'])->group(function (){
            Route::get('/cart', 'cart')->name('cart.index');
            Route::get('/profile', 'profile')->name('profile');
            Route::get('/message', 'message')->name('message');
            Route::get('/order/details-user/{id}', 'orderDetails')->name('order.details-user');
            Route::get('/order/details-order/{id}', 'detailsOrder')->name('order.details-order');
        });
    });
});


require __DIR__.'/auth.php';
