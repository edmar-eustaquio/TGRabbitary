<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MessageController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('set-last-online')->group(function () {
    Route::controller(\App\Http\Controllers\ProductController::class)->name('product.')->prefix('product')->group(function (){
        Route::get('/get-user', 'getUser')->name('get-user');
        Route::get('/get-user-scroll', 'getUserScroll')->name('get-user-scroll');
    });
    Route::controller(\App\Http\Controllers\BlogController::class)->name('blog.')->prefix('blog')->group(function (){
        Route::get('/get-scroll', 'getScroll')->name('get-scroll');
    });

    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        Route::controller(\App\Http\Controllers\ProfileController::class)->name('profile.')->prefix('profile')->group(function (){
            Route::post('/', 'update')->name('update');
            Route::post('/update-password', 'updatePassword')->name('update-password');
        });
        
        Route::controller(\App\Http\Controllers\BlogController::class)->name('blog.')->prefix('blog')->group(function (){
            Route::post('/store', 'store')->name('store');
            Route::post('/update/{id}', 'update')->name('update');
            Route::post('/delete/{id}', 'delete')->name('delete');
            Route::get('/get', 'get')->name('get');
        });
        Route::controller(\App\Http\Controllers\CommentController::class)->name('comment.')->prefix('comment')->group(function (){
            Route::post('/store', 'store')->name('store');
            Route::post('/reply', 'reply')->name('reply');
            Route::get('/get', 'get')->name('get');
        });

        Route::middleware('role:Admin')->group(function () {
            Route::controller(\App\Http\Controllers\ProductController::class)->name('product.')->prefix('product')->group(function (){
                Route::get('/get', 'get')->name('get');
                Route::get('/rabbits', 'rabbits')->name('rabbits');
                Route::get('/dropdown', 'dropdown')->name('dropdown');
                Route::post('/store', 'store')->name('store');
                Route::post('/update/{id}', 'update')->name('update');
                Route::post('/delete/{id}', 'delete')->name('delete');
            });

            Route::controller(\App\Http\Controllers\SettingController::class)->name('setting.')->prefix('setting')->group(function (){
                Route::post('/update-deal', 'updateDeal')->name('update-deal');
                Route::post('/update-number', 'updateNumber')->name('update-number');
                Route::post('/update-quote', 'updateQuote')->name('update-quote');
            });

            Route::controller(MessageController::class)->name('admin.message.')->prefix('admin.message')->group(function(){
                Route::get('/get/{user_id}', 'getAdmin')->name('get');
                Route::post('/send-message', 'sendMessageAdmin')->name('send-message');
                Route::post('/send-image', 'sendImageAdmin')->name('send-image');
            });
            
            Route::controller(OrderController::class)->name('order.')->prefix('order')->group(function (){
                Route::get('/get', 'get')->name('get');
                Route::post('/update-status/{id}', 'updateStatus')->name('update-status');
                Route::post('/mark-as-paid/{id}', 'markAsPaid')->name('mark-as-paid');
            });

            Route::controller(\App\Http\Controllers\BudgetController::class)->name('budget.')->prefix('budget')->group(function (){
                Route::post('/store', 'store')->name('store');
                Route::post('/update/{id}', 'update')->name('update');
                Route::post('/delete/{id}', 'delete')->name('delete');
                Route::get('/get', 'get')->name('get');
                Route::get('/get-expense', 'getExpense')->name('get-expense');
                Route::get('/get-range', 'getRange')->name('get-range');
            });

            Route::controller(\App\Http\Controllers\CategoryController::class)->name('category.')->prefix('category')->group(function (){
                Route::post('/store', 'store')->name('store');
                Route::post('/update/{id}', 'update')->name('update');
                Route::post('/delete/{id}', 'delete')->name('delete');
                Route::get('/get', 'get')->name('get');
                Route::get('/get-all', 'getAll')->name('get-all');
            });

            Route::controller(\App\Http\Controllers\RabbitController::class)->name('rabbit.')->prefix('rabbit')->group(function (){
                Route::post('/store', 'store')->name('store');
                Route::post('/update/{id}', 'update')->name('update');
                Route::post('/delete/{id}', 'delete')->name('delete');
                Route::get('/dropdown', 'dropdown')->name('dropdown');
                Route::get('/get', 'get')->name('get');
                Route::get('/breeds', 'breeds')->name('breeds');
                Route::get('/get-bucks', 'getBucks')->name('get-bucks');
                Route::get('/get-does', 'getDoes')->name('get-does');
            });

            Route::controller(\App\Http\Controllers\BreedingController::class)->name('rabbit.breeding.')->prefix('rabbit/breeding')->group(function (){
                Route::post('/store', 'store')->name('store');
                Route::post('/update/{id}', 'update')->name('update');
                Route::post('/delete/{id}', 'delete')->name('delete');
                Route::get('/get', 'get')->name('get');
            });

            Route::controller(\App\Http\Controllers\IllnessController::class)->name('rabbit.illness.')->prefix('rabbit/illness')->group(function (){
                Route::post('/store', 'store')->name('store');
                Route::post('/update-status', 'updateStatus')->name('update-status');
                Route::get('/get', 'get')->name('get');
                Route::get('/dropdown', 'dropdown')->name('dropdown');
            });

            Route::controller(\App\Http\Controllers\TaskController::class)->name('task.')->prefix('task')->group(function (){
                Route::post('/store', 'store')->name('store');
                Route::post('/update-completed/{id}', 'updateCompleted')->name('update-completed');
                Route::post('/update/{id}', 'update')->name('update');
                Route::post('/delete/{id}', 'delete')->name('delete');
                Route::get('/get-completed', 'getCompleted')->name('get-completed');
                Route::get('/get', 'get')->name('get');
            });

            Route::controller(\App\Http\Controllers\WeightController::class)->name('rabbit.weight.')->prefix('rabbit/weight')->group(function (){
                Route::post('/add', 'add')->name('add');
                Route::get('/get/{rabbit_id}', 'get')->name('get');
            });

            Route::get('/log/get', [\App\Http\Controllers\LogController::class, 'get'])->name('log.get');
        });
        
        Route::middleware(['role:User'])->group(function () {
            Route::controller(OrderController::class)->name('order.')->prefix('order')->group(function (){
                Route::get('/get-user', 'getUser')->name('get-user');
            });

            Route::controller(\App\Http\Controllers\CartController::class)->name('cart.')->prefix('cart')->group(function (){
                Route::get('/get', 'get')->name('get');
                Route::get('/count', 'count')->name('count');
                Route::post('/add', 'add')->name('add');
                Route::post('/checkout', 'checkout')->name('checkout');
                Route::post('/buy-now', 'buyNow')->name('buy-now');
                Route::post('/update-qty/{id}', 'updateQty')->name('update-qty');
                Route::post('/delete/{id}', 'delete')->name('delete');
                Route::post('/cancel/{id}', 'cancel')->name('cancel');
            });

            Route::controller(\App\Http\Controllers\RatingController::class)->name('rating.')->prefix('rating')->group(function(){
                Route::post('/add', 'add')->name('add');
            });

            Route::controller(MessageController::class)->name('message.')->prefix('message')->group(function(){
                Route::get('/get', 'get')->name('get');
                Route::post('/send-message', 'sendMessage')->name('send-message');
                Route::post('/send-image', 'sendImage')->name('send-image');
            });
        });
    });
});