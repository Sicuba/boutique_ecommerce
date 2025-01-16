<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CartController extends Controller
{
    public function addCartProduct($customer_id, $product_id,$variant_id, $quantity_type){

        $userData = session('user_data');
          /* Adicionando a um determinado produto */
          $headers = [
            'Authorization' => "Bearer ".$userData['token'],
            'Content-Type' => 'application/json',
        ];
        $conf = [
            'customer_id' => $customer_id,
            'product_id' => $product_id,
            'variant_id' => $variant_id,
            'quantity_type' => $quantity_type
        ];


        try {
            // Fazendo a requisição POST
            $response_prod = Http::withHeaders($headers)->post('https://demo.vitrinedigital.eu/api/boutique-da-cosmtica/cart-qty', $conf);

            // Verificando se a requisição foi bem-sucedida
            if ($response_prod->successful()) {
                // Processando a resposta
                $data = $response_prod->json(); // Supondo que a API retorna um JSON
                return redirect()->route('cart');
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

    public function removeCartProduct($customer_id, $product_id,$variant_id, $quantity_type){
        $userData = session('user_data');
        /* Adicionando a um determinado produto */
        $headers = [
          'Authorization' => "Bearer ".$userData['token'],
          'Content-Type' => 'application/json',
      ];
      $conf = [
          'customer_id' => $customer_id,
          'product_id' => $product_id,
          'variant_id' => $variant_id,
          'quantity_type' => $quantity_type
      ];


      try {
          // Fazendo a requisição POST
          $response_prod = Http::withHeaders($headers)->post('https://demo.vitrinedigital.eu/api/boutique-da-cosmtica/cart-qty', $conf);

          // Verificando se a requisição foi bem-sucedida
          if ($response_prod->successful()) {
              // Processando a resposta
              $data = $response_prod->json(); // Supondo que a API retorna um JSON
              return redirect()->route('cart');
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
}
