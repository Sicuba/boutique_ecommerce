<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    public function index(){

        $countries = null;

        /* GET COUNTRY */
        try {
            // Fazendo a requisição POST
            $response_prod = Http::post('https://demo.vitrinedigital.eu/api/boutique-da-cosmtica/country-list');

            // Verificando se a requisição foi bem-sucedida
            if ($response_prod->successful()) {
                // Processando a resposta
                $data = $response_prod->json(); // Supondo que a API retorna um JSON
                $countries = $data['data'];

            } else {
                dd('Erro na requisição');
            }
        } catch (\Exception $e) {
            // Tratando exceções (erros de conexão, etc.)
           dd('Erro', $e);
        }

        return view('auth.register', ['countries'=>$countries]);
    }
    public function store(Request $request){
        /* Buscando os produtos */
        $payload = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => $request->password,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'city_id' => $request->city_id,
        ];
        try {
            // Fazendo a requisição POST
            $response_prod = Http::post('https://demo.vitrinedigital.eu/api/boutique-da-cosmtica/register', $payload);

            // Verificando se a requisição foi bem-sucedida
            if ($response_prod->successful()) {
                // Processando a resposta
                $data = $response_prod->json(); // Supondo que a API retorna um JSON

               session(['user_data' => $data['data']]);
               return redirect()->route('home');
            } else {
                dd('Erro ao registrar!');
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
