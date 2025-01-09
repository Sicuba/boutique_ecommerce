<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SessionController;

/* Route::get('/', function () {
    return view('welcome');
}); */
/* Route::get('/','ProductController@getHomePageProducts')->name('home'); */
Route::get('/', [ProductController::class,'getHomePageProducts'])->name('home');
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'store'])->name('login.store');
Route::post('/savesession', [SessionController::class, 'saveSesssion'])->name('session.save');
Route::post('/logout',function () {
    session()->forget('user_data');
    session()->forget('cart');
    return redirect()->route('home');
})->name('logout');

Route::get('shop','App\Http\Controllers\ProductController@getProducts')->name('shop');

Route::get('cart','App\Http\Controllers\ProductController@getCart')->name('cart');

Route::get('address',function () {
    return view('address');
})->name('user.address');

Route::get('address/{id}','App\Http\Controllers\AddressController@get')->name('address.get');

Route::post('address','App\Http\Controllers\AddressController@store')->name('address.store');

Route::post('addressupdate','App\Http\Controllers\AddressController@update')->name('address.update');

Route::get('product/{id}','App\Http\Controllers\ProductController@getSingleProduct')->name('product');

Route::get('product/category/{id}','App\Http\Controllers\ProductController@getProductsByCategory')->name('category.products');

Route::get('product/addToCart/{id}','App\Http\Controllers\ProductController@addToCart')->name('product.addToCart');

Route::get('product/removeFromCart/{id}','App\Http\Controllers\ProductController@removeFromCart')->name('product.removeFromCart');

Route::get('/checkout', function () {
    $userData = session('user_data');
    return view('checkout',['data'=>$userData]);
})->name('checkout');

Route::get('/contact', function () {
    $userData = session('user_data');
    if(isset($userData)){
        /* Buscando dados no carrinho */
        $headers = [
            'Authorization' => "Bearer ".$userData['token'],
            'Content-Type' => 'application/json',
        ];
        $conf = [
            'costumer_id' => $userData['id'],
        ];

        try {
            // Fazendo a requisição POST
            $response_prod = Http::withHeaders($headers)->post('https://demo.vitrinedigital.eu/api/boutique-da-cosmtica/cart-list', $conf);

            // Verificando se a requisição foi bem-sucedida
            if ($response_prod->successful()) {
                // Processando a resposta
                $data = $response_prod->json(); // Supondo que a API retorna um JSON
                session(['cart' => $data['data']['product_list']]);
            } else {
            dd('Não deu certo');
            }
        } catch (\Exception $e) {
            // Tratando exceções (erros de conexão, etc.)
            \Log::error('Erro ao fazer requisição POST: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro interno: ' . $e->getMessage(),
            ], 500);
        }
    }
    return view('contact',['data'=>$userData]);
})->name('contact');

Route::get('/about', function () {
    $userData = session('user_data');
    if(isset($userData)){
        /* Buscando dados no carrinho */
        $headers = [
            'Authorization' => "Bearer ".$userData['token'],
            'Content-Type' => 'application/json',
        ];
        $conf = [
            'costumer_id' => $userData['id'],
        ];

        try {
            // Fazendo a requisição POST
            $response_prod = Http::withHeaders($headers)->post('https://demo.vitrinedigital.eu/api/boutique-da-cosmtica/cart-list', $conf);

            // Verificando se a requisição foi bem-sucedida
            if ($response_prod->successful()) {
                // Processando a resposta
                $data = $response_prod->json(); // Supondo que a API retorna um JSON
                session(['cart' => $data['data']['product_list']]);
            } else {
            dd('Não deu certo');
            }
        } catch (\Exception $e) {
            // Tratando exceções (erros de conexão, etc.)
            \Log::error('Erro ao fazer requisição POST: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro interno: ' . $e->getMessage(),
            ], 500);
        }
    }
    return view('about', ['data'=>$userData]);
})->name('about');

Route::post('checkout','App\Http\Controllers\PaymentController@createRequest')->name('createPaymentRequest');

Route::get('redirect/payment/','App\Http\Controllers\PaymentController@getAcknowledgement')->name('payment.successful');



