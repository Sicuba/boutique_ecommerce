<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LocationController extends Controller
{
    public function getStates($countryId)
    {
        /* GET SATES */
        $payload = [
            'country_id' => $countryId,
        ];
        $states = null;
        try {
            // Fazendo a requisição POST
            $response_prod = Http::post('https://demo.vitrinedigital.eu/api/boutique-da-cosmtica/state-list', $payload);

            // Verificando se a requisição foi bem-sucedida
            if ($response_prod->successful()) {
                // Processando a resposta
               $data = $response_prod->json(); // Supondo que a API retorna um JSON
               $states = $data['data'];
               return response()->json($states);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erro na requisição!',
                ], 500);
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

    public function getCities($stateId)
    {
        /* GET CITIES */
        $payload = [
            'state_id' => $stateId,
        ];
        $cities = null;
        try {
            // Fazendo a requisição POST
            $response_prod = Http::post('https://demo.vitrinedigital.eu/api/boutique-da-cosmtica/city-list', $payload);

            // Verificando se a requisição foi bem-sucedida
            if ($response_prod->successful()) {
                // Processando a resposta
               $data = $response_prod->json(); // Supondo que a API retorna um JSON
               $cities = $data['data'];
               return response()->json($cities);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Erro na requisição!',
                ], 500);
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
