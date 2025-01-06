<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function saveSesssion(Request $request){
        try {
            // Verificar se os dados necessários estão presentes
            if (!$request->has(['token', 'id'])) {
                return response()->json(['status' => 'error', 'message' => 'Dados incompletos'], 400);
            }

            // Armazenar dados na sessão
            $data = $request->all();
            session(['user_data' => $data]);

            return response()->json(['status' => 'success', 'message' => 'Dados armazenados na sessão']);
        } catch (\Exception $e) {
            // Registrar erro no log
            \Log::error('Erro ao salvar na sessão: ' . $e->getMessage());

            return response()->json(['status' => 'error', 'message' => 'Erro interno no servidor'], 500);
        }
    }
}
