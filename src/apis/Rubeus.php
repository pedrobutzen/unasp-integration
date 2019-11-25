<?php

namespace unasp;

use Config;
use Illuminate\Support\Facades\DB;

class Rubeus {
    public static $invokeURL = "https://crmunasp.rubeus.com.br/api";
    public static $invokeURLAPI = "https://api.apprbs.com.br/api";
    
    public static $endpoints = [
        "dados_contato" => "/Contato/dadosPessoa",
        "contato" => "/Contato/cadastro",
        "evento" => "/Evento/cadastro",
    ];

    public static function call(string $endpoint, $method, array $data = []) {
        $data = array_merge($data, [
            "origem" => 5, # Processo Seletivo Próprio
            "token" => Config::get('unasp_integrations.RUBEUS_TOKEN'),
        ]);

        $data = array_merge(["api" => false,], $data);
        $url = ($data['api'] ? self::$invokeURLAPI : self::$invokeURL) . self::$endpoints[$endpoint] . ($data['api'] ? '?clnt=unasp' : '');
        unset($data['api']);

        $ch = curl_init();

        if ($ch === false) {
            return ['status' => '500', 'data' => 'Error on cURL initialization.'];
        }

        $request = json_encode($data);

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
            CURLOPT_POST => $method == 'post',
            CURLOPT_POSTFIELDS => $request,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 10,
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            $response = curl_error($ch);
        }

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $total_time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
        
        DB::table('integrator_log')->insert([
            'date' => date("Y-m-d H:i:s"),
            'api' => 'rubeus',
            'url' => $url,
            'method' => $method,
            'request' => $request,
            'duration_in_seconds' => $total_time,
            'response_code' => $http_code,
            'response_body' => $response,
        ]);

        curl_close($ch);

        if (json_last_error() == JSON_ERROR_NONE)
            $response = json_decode($response);

        return ['status' => $http_code, 'data' => $response];
    }
}
