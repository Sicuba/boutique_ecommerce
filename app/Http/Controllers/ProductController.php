<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    public function getSingleProduct($id)
    {
        $product = Product::find($id);
        $relatedProducts = Product::limit(4)->get();
        return view('product', ['product' => $product,'relatedProducts' => $relatedProducts]);
    }

    public function getProducts(Request $request)
    {
        $page = 1;
        $searchTerm = $request->query('page');

        if($request->query('page')){
            $page = (int)$searchTerm;
        }else{
            $page = 1;
        }

        $userData = session('user_data');
        if (!$userData) {
            return redirect()->route('login')->withErrors('Você precisa fazer login primeiro.');
        }
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

        /* Buscando os produtos */
        $payload = [
            'page' => $page,
        ];
        $products_paginate = [];
        try {
            // Fazendo a requisição POST
            $response_prod = Http::post('https://demo.vitrinedigital.eu/api/boutique-da-cosmtica/recent-product', $payload);

            // Verificando se a requisição foi bem-sucedida
            if ($response_prod->successful()) {
                // Processando a resposta
                $data = $response_prod->json(); // Supondo que a API retorna um JSON
                /* dd($data['data']['data']); */
                $products = $data['data']['data'];
                $products_paginate = $data['data'];
                /* dd($products_paginate); */
            } else {
                $products = [];
            }
        } catch (\Exception $e) {
            // Tratando exceções (erros de conexão, etc.)
            \Log::error('Erro ao fazer requisição POST: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro interno: ' . $e->getMessage(),
            ], 500);
        }

        // Busca as categorias de uma API externa
        try {
            $response = Http::post('https://demo.vitrinedigital.eu/api/boutique-da-cosmtica/category-list'); // Substitua pela URL real da API
            if ($response->successful()) {
                $all_categories = $response->json(); // Supondo que a API retorna um array de categorias
                $categories = $all_categories['data'];
            } else {
                $categories = []; // Em caso de falha, retorna um array vazio
            }
        } catch (\Exception $e) {
            // Trate erros de conexão ou outros
            $categories = [];
        }


        return view('shop', ['products' => $products,'categories' => $categories,'selectedCategory' => -1, 'data'=>$userData, 'pagination' => [
                'current_page' => $products_paginate['current_page'],
                'last_page' => $products_paginate['last_page'],
                'next_page_url' => $products_paginate['next_page_url'],
                'prev_page_url' => $products_paginate['prev_page_url'],
                'total' => $products_paginate['total'],
            ]]);
    }

    public function getProductsByCategory($id) {
        $userData = session('user_data');
         /* Buscando os produtos pela categoria */
         $payload = [
            'main_category_id' => $id,
        ];
        $products_paginate = [];
        try {
            // Fazendo a requisição POST
            $response_prod = Http::post('https://demo.vitrinedigital.eu/api/boutique-da-cosmtica/tranding-category-product', $payload);

            // Verificando se a requisição foi bem-sucedida
            if ($response_prod->successful()) {
                // Processando a resposta
                $data = $response_prod->json(); // Supondo que a API retorna um JSON
                $products = $data['data']['data'];
                $products_paginate = $data['data'];
            } else {
                $products = [];
            }
        } catch (\Exception $e) {
            // Tratando exceções (erros de conexão, etc.)
            \Log::error('Erro ao fazer requisição POST: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro interno: ' . $e->getMessage(),
            ], 500);
        }

        // Busca as categorias de uma API externa
        try {
            $response = Http::post('https://demo.vitrinedigital.eu/api/boutique-da-cosmtica/category-list'); // Substitua pela URL real da API
            if ($response->successful()) {
                $all_categories = $response->json(); // Supondo que a API retorna um array de categorias
                $categories = $all_categories['data'];
            } else {
                $categories = []; // Em caso de falha, retorna um array vazio
            }
        } catch (\Exception $e) {
            // Trate erros de conexão ou outros
            $categories = [];
        }


        return view('shop', ['products' => $products,'categories' => $categories,'selectedCategory' => $id, 'data'=>$userData, 'pagination' => [
                'current_page' => $products_paginate['current_page'],
                'last_page' => $products_paginate['last_page'],
                'next_page_url' => $products_paginate['next_page_url'],
                'prev_page_url' => $products_paginate['prev_page_url'],
                'total' => $products_paginate['total'],
            ]]);
    }

    public function getProductsByLimit($limit)
    {
        return Product::limit($limit)->get();
    }

    public function addToCart(Request $request, $id) {
        /* if (!$this->isUserLoggedIn()) {
            return redirect()->route('login');
        } */

        $userData = session('user_data');
        if (!$userData) {
            return redirect()->route('login')->withErrors('Você precisa fazer login primeiro.');
        }

         /* Buscando um determinado produto */
         $payload = [
            'type' => 'product_filter',
            'name' => $id
        ];

        try {
            // Fazendo a requisição POST
            $response_prod = Http::post('https://demo.vitrinedigital.eu/api/boutique-da-cosmtica/search', $payload);

            // Verificando se a requisição foi bem-sucedida
            if ($response_prod->successful()) {
                // Processando a resposta
                $data = $response_prod->json(); // Supondo que a API retorna um JSON
                 /* dd($data['data']['data'][0]); */
                $product = $data['data']['data'][0];


            } else {
                $product = [];
            }
        } catch (\Exception $e) {
            // Tratando exceções (erros de conexão, etc.)
            \Log::error('Erro ao fazer requisição POST: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro interno: ' . $e->getMessage(),
            ], 500);
        }

        /* Adicionando ao carrinho */

        $conf_cart = [
            'customer_id' => $userData['id'],
            'product_id' => $product['id'],
            'qty' => 1
        ];

        try {
            // Fazendo a requisição POST
            $response_prod = Http::post('https://demo.vitrinedigital.eu/api/boutique-da-cosmtica/add-cart', $conf_cart);

            // Verificando se a requisição foi bem-sucedida
            if ($response_prod->successful()) {
                // Processando a resposta
                $data = $response_prod->json(); // Supondo que a API retorna um JSON
                 /* dd($data['data']['data'][0]); */



            } else {
                dd('Erro na requisição');
            }
        } catch (\Exception $e) {
            // Tratando exceções (erros de conexão, etc.)
            \Log::error('Erro ao fazer requisição POST: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro interno: ' . $e->getMessage(),
            ], 500);
        }

        return redirect()->back();
    }

    public function removeFromCart($id) {
        $userData = session('user_data');

        $customer_id = $userData['id'];
        $product_id = $id;
        $variant_id = 0;
        $quantity_type = 'remove';

        $body = [
            'customer_id' => $customer_id,
            'product_id' => $product_id ,
            'variant_id' =>  $variant_id,
            'quantity_type' => $quantity_type
        ];
        /* Removendo... */
        try {
            // Fazendo a requisição POST
            $response_prod = Http::post('https://demo.vitrinedigital.eu/api/boutique-da-cosmtica/cart-qty', $body);

            // Verificando se a requisição foi bem-sucedida
            if ($response_prod->successful()) {
                // Processando a resposta
                $data = $response_prod->json(); // Supondo que a API retorna um JSON

            } else {
                dd('Erro na requisição');
            }
        } catch (\Exception $e) {
            // Tratando exceções (erros de conexão, etc.)
            \Log::error('Erro ao fazer requisição POST: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro interno: ' . $e->getMessage(),
            ], 500);
        }

      /*   $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);
        $cart->remove($id);
        Session::put('cart', $cart); */
        return redirect()->back();
    }

    public function getCart() {
        /* if (!$this->isUserLoggedIn()) {
            return redirect()->route('login');
        } */

        $userData = session('user_data');
        if (!$userData) {
            return redirect()->route('login')->withErrors('Você precisa fazer login primeiro.');
        }
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

                $products = $data['data']['product_list'];
                $totalPrice = $data['data']['total_final_price'];
                /* dd($data['data']['product_list']); */
                session(['cart' => $data['data']['product_list']]);
                /* $products = $data['data']['data'];
                $products_paginate = $data['data']; */
                return view('cart',['products'=> $products,'totalPrice'=> $totalPrice, 'data'=>$userData]);
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

    public function getHomePageProducts()
    {
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
         /* Buscando os produtos */
         $payload = [
            'page' => 1,
        ];

        try {
            // Fazendo a requisição POST
            $response_prod = Http::post('https://demo.vitrinedigital.eu/api/boutique-da-cosmtica/recent-product', $payload);

            // Verificando se a requisição foi bem-sucedida
            if ($response_prod->successful()) {
                // Processando a resposta
                $data = $response_prod->json(); // Supondo que a API retorna um JSON
                /* dd($data['data']['data']); */
                $products = $data['data']['data'];
                $products_paginate = $data['data'];
            } else {
                $products = [];
            }
        } catch (\Exception $e) {
            // Tratando exceções (erros de conexão, etc.)
            \Log::error('Erro ao fazer requisição POST: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro interno: ' . $e->getMessage(),
            ], 500);
        }

        return view('home', ['products' => $products, 'data'=> $userData]);
    }

    private function isUserLoggedIn() {
        return Auth::check();
    }



}
