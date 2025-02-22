<?php

use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\Feature\OrderController;
use App\Http\Controllers\Backend\Master\CategoryController;
use App\Http\Controllers\Backend\Master\ProductController;
use App\Http\Controllers\Frontend\AccountController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Frontend\TransacationController;
use App\Http\Controllers\Midtrans\MidtransController;
use App\Http\Controllers\Rajaongkir\RajaongkirController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\likeController;
use App\Http\Controllers\Setting\WebconfigController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Contracts\Role;
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use BotMan\BotMan\Messages\Incoming\Answer;
use App\Events\ChatMessageSent;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('payments/midtrans-notification', [MidtransController::class, 'receive']);
Route::get('payments/midtrans-success', [MidtransController::class, 'success']);
// Route::get('/chat', function () {
//     return view('chatbot');
// });
// ... existing code ...
Route::match(['get', 'post'], '/botman', function() {
    $botman = app('botman');

    // Percakapan awal
    $botman->hears('halo|hi|hey', function (BotMan $bot) {
        $bot->reply('Halo juga! Saya adalah bot yang siap membantu Anda.');
        $bot->ask('Boleh tau nama kamu siapa?', function(Answer $answer, $bot) {
            $name = $answer->getText();
            
            $bot->say('Senang berkenalan dengan Anda ' . $name . '!');
            $bot->ask('Bagaimana kabar Anda hari ini ' . $name . '?', function(Answer $answer, $bot) {
                $response = $answer->getText();
                $bot->say('Senang mendengarnya');
            });
            $bot->ask('Boleh minta alamat email kamu?', function(Answer $answer, $bot) {
                $email = $answer->getText();
                
                $bot->say('Email anda kami simpan sebagai data kami' . $email. 'Ada yang bisa saya bantu? Ketik "Bantuan" untuk melihat menu.');
            });
        });
    });

    // Menu bantuan
    $botman->hears('Bantuan', function(BotMan $bot) {
        $bot->reply('Berikut beberapa hal yang bisa saya bantu: 
        1. Informasi produk </br>
        2. Cara pemesanan </br>
        3. Status pesanan</br>
        4. Metode Pembayaran</br>
        5. Hubungi Kami</br>
        
        Silakan ketik nomor atau menu yang Anda inginkan.');
    });

    // Respon untuk pilihan menu
    $botman->hears('1|Informasi produk', function(BotMan $bot) {
        $bot->reply('Kami menyediakan berbagai produk berkualitas. Silakan kunjungi halaman menu "produk kami" <a href="https://anekabarangsby.my.id/product">Klik di sini</a> di website untuk informasi lebih detail.');
    });

    $botman->hears('2|Cara pemesanan', function(BotMan $bot) {
        $bot->reply('Untuk melakukan pemesanan:
        1. Pilih produk yang diinginkan
        2. Masukkan ke keranjang
        3. Klik checkout
        4. Isi data pengiriman
        5. Pilih metode pembayaran
        6. Selesaikan pembayaran');
    });

    $botman->hears('3|Status pesanan', function(BotMan $bot) {
        $bot->reply('Untuk mengecek status pesanan, silakan login ke akun Anda dan kunjungi menu "Transaksi"<a href="https://anekabarangsby.my.id/transaction">Klik di sini</a>.');
    });
    $botman->hears('4|Pilih metode pembayaran', function(BotMan $bot) {
        $bot->reply('Kami menyediakan berbagai metode pembayaran, termasuk QRIS, semua jenis bank, dan kartu kredit.');
    });
    $botman->hears('5|Hubungi Kami', function(BotMan $bot) {
        $bot->reply('Untuk hubungi kami, silakan klik link ini : <a href="https://anekabarangsby.my.id/chat">Klik di sini</a>.');
    });
   
    // Percakapan umum
    // $botman->hears('Gimana kabar?', function(BotMan $bot) {
    //     $bot->reply('Alhamdulillah baik! Bagaimana dengan Anda?');
    // });

    $botman->hears('terima kasih', function(BotMan $bot) {
        $bot->reply('Sama-sama! Senang bisa membantu Anda.');
    });

    $botman->listen();
});

// Route::match(['get', 'post'], '/botman/webhook', function () {
//     $botman = app('botman');

//     $botman->hears('hello', function (BotMan $bot) {
//         $message = 'Hello! How can I help you?';
//         $bot->reply($message);
//         event(new ChatMessageSent($message));
//     });

//     $botman->hears('goodbye', function (BotMan $bot) {
//         $message = 'Goodbye! Have a great day!';
//         $bot->reply($message);
//         event(new ChatMessageSent($message));
//     });

//     $botman->listen();
// });
// ... existing code ...
Route::get('/get-likes/{name}', [LikeController::class, 'getLikes'])->name('product.getLikes');
Route::prefix('app')->group(function () {
    

    Route::middleware(['auth'])->group(function () {

        Route::get('dashboard',[DashboardController::class,'index'])->name('admin.dashboard');
        Route::get('/chatadmin', [ChatController::class, 'showChatadmin'])->name('chat.showadmin');
        Route::get('/chatadmin/{receiverId}', [ChatController::class, 'showChatadmin'])->name('chat.showadmin');
        Route::post('/chat/sendadmin', [ChatController::class, 'sendMessageadmin'])->name('chat.sendadmin');
        Route::post('/chat/endadmin', [ChatController::class, 'endChat'])->name('chat.endadmin');


        Route::prefix('customer')->name('customer.')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('index');
        });

        Route::prefix('master')->name('master.')->group(function(){
            
            Route::prefix('category')->name('category.')->group(function(){
                Route::get('/',[CategoryController::class,'index'])->name('index');
                Route::get('/create',[CategoryController::class,'create'])->name('create');
                Route::post('/create',[CategoryController::class,'store'])->name('store');
                Route::get('/delete/{id}',[CategoryController::class,'delete'])->name('delete');
                Route::get('/edit/{id}',[CategoryController::class,'edit'])->name('edit');
                Route::post('/update/{id}',[CategoryController::class,'update'])->name('update');
                Route::get('/show/{id}',[CategoryController::class,'show'])->name('show');
            });

            Route::prefix('product')->name('product.')->group(function(){
                Route::get('/',[ProductController::class,'index'])->name('index');
                Route::get('/create',[ProductController::class,'create'])->name('create');
                Route::post('/create',[ProductController::class,'store'])->name('store');
                Route::get('/show/{id}',[ProductController::class,'show'])->name('show');
                Route::get('/edit/{id}',[ProductController::class,'edit'])->name('edit');
                Route::post('/update/{id}',[ProductController::class,'update'])->name('update');
                Route::get('/delete/{id}',[ProductController::class,'delete'])->name('delete');
            });

        });

        Route::prefix('feature')->name('feature.')->group(function(){

            Route::prefix('order')->name('order.')->group(function(){
                Route::get('/{status?}',[OrderController::class,'index'])->name('index');
                Route::get('/detail/{id}',[OrderController::class,'show'])->name('show');
                Route::post('/detail/input-resi',[OrderController::class,'inputResi'])->name('inputresi');
            });

        });

        Route::prefix('setting')->name('setting.')->group(function(){
                Route::get('/shipping',[WebconfigController::class,'shipping'])->name('shipping');
                Route::post('/shipping',[WebconfigController::class,'shippingUpdate'])->name('shippingUpdate');
                
                Route::get('/web',[WebconfigController::class,'web'])->name('web');
                Route::post('/web',[WebconfigController::class,'webUpdate'])->name('web.update');
        });

    });

});

Route::middleware('auth','role:user')->group(function(){
   
    Route::post('/like/{name}', [LikeController::class, 'likeProduct'])->name('product.like');
    Route::post('/apply-voucher', [CheckoutController::class, 'apply'])->name('apply.voucher');
    Route::post('/send-message', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::get('/chat', [ChatController::class, 'showChat'])->name('chat.show');
    Route::post('/chat/end', [ChatController::class, 'endChat'])->name('chat.end');

    Route::prefix('cart')->name('cart.')->group(function(){
        Route::get('/',[CartController::class,'index'])->name('index');
        Route::post('/store',[CartController::class,'store'])->name('store');
        Route::post('/update',[CartController::class,'update'])->name('update');
        Route::get('/delete/{id}',[CartController::class,'delete'])->name('delete');
    });

    Route::prefix('transaction')->name('transaction.')->group(function(){
        Route::get('/',[TransacationController::class,'index'])->name('index');
        Route::get('/{invoice_number}',[TransacationController::class,'show'])->name('show');
        Route::get('/{invoice_number}/received',[TransacationController::class,'received'])->name('received');
        Route::get('/{invoice_number}/canceled',[TransacationController::class,'canceled'])->name('canceled');
    });

    Route::prefix('checkout')->name('checkout.')->group(function(){
        Route::get('/',[CheckoutController::class,'index'])->name('index');
        Route::post('/process',[CheckoutController::class,'process'])->name('process');
    });

    Route::prefix('account')->name('account.')->group(function(){
        Route::get('/',[AccountController::class,'index'])->name('index');
        Route::put('/profiles', [AccountController::class, 'update'])->name('profiles.update');
        Route::put('/profiles/account', [AccountController::class, 'updateaccount'])->name('profiles.updateaccount');
    });

    Route::get('/products/{productId}/reviews', [ReviewController::class, 'showReviews'])->name('products.reviews');

    // Route untuk menyimpan review (POST)
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');
});

Route::prefix('rajaongkir')->name('rajaongkir.')->group(function(){
    Route::post('/cost',[RajaongkirController::class,'cost'])->name('cost');
    Route::get('/province/{id}',[RajaongkirController::class,'getCity'])->name('city');
});


Route::get('/', [HomeController::class,'index'])->name('home');
Route::get('/contact', [HomeController::class,'contact'])->name('contact');
// Route Product
Route::get('/product', [FrontendProductController::class,'index'])->name('product.index');

Route::get('/search',[FrontendProductController::class,'search'])->name('product.search');

// Ruote Category
Route::get('/category', [FrontendCategoryController::class,'index'])->name('category.index');
Route::get('/category/{slug}', [FrontendCategoryController::class,'show'])->name('category.show');



Route::get('/product/{categoriSlug}/{productSlug}',[FrontendProductController::class,'show'])->name('product.show');


require __DIR__ . '/auth.php';
