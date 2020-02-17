<?php

namespace unasp;

use Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;

class Rubeus {
    public static $invokeURL = "https://crmunasp.rubeus.com.br/api";
    public static $invokeURLAPI = "https://api.apprbs.com.br/api";
    
    public static $endpoints = [
        "dados_contato" => "/Contato/dadosPessoa",
        "contato" => "/Contato/cadastro",
        "evento" => "/Evento/cadastro",
    ];

    public static function call(string $endpoint, $method, $data = [], int $retry_id = null) {
        $data = array_merge([
            "api" => false,
            "return_response" => false,
        ], $data);

        if(is_null($retry_id)) {
            $data = array_merge($data, [
                "origem" => 5, # Processo Seletivo Próprio
                "token" => Config::get('unasp_integrations.RUBEUS_TOKEN'),
            ]);

            $url = ($data['api'] ? 
                self::$invokeURLAPI : 
                self::$invokeURL) . self::$endpoints[$endpoint] . ($data['api'] ? '?clnt=unasp' : '');

            $request_body = json_encode($data);
        } else {
            $url = $endpoint;
            $request_body = $data;
        }

        $return_response = $data['return_response'];

        unset($data['api']);
        unset($data['return_response']);

        $client = new Client();
        $total_time = 0;

        $time_start = microtime(1);
        try {
            $response = $client->request($method, $url, [
                'headers'         => [
                    'Content-Type' => 'application/json',
                ],
                'body'            => $request_body,
                'connect_timeout' => 5,
                'timeout'         => 10,
            ]);

            $response_body = $response->getBody()->getContents();
            $response_body_object = json_decode($response_body);

            $http_code = !$return_response ? ($response_body_object && !$response_body_object->success ? 400 : $response->getStatusCode()) : $response->getStatusCode();
        } catch (RequestException $e) {
            $response = $e->hasResponse() ? $e->getResponse() : NULL;

            $response_body = !is_null($response) ? $response->getBody()->getContents() : $e->getMessage();
            $http_code = !is_null($response) ? $response->getStatusCode() : (strstr($response_body, 'Operation timed out after') ? 408 : 0);
        }
        $time_end = microtime(1);
        $total_time = $time_end - $time_start;

        if(is_null($retry_id)) {
            DB::table('integrator_log')->insert([
                'date' => date("Y-m-d H:i:s"),
                'api' => 'rubeus',
                'url' => $url,
                'method' => $method,
                'request' => $request_body,
                'duration_in_seconds' => $total_time,
                'response_code' => $http_code,
                'response_body' => $response_body,
            ]);
        } else {
            DB::table('retry_integrator_log')->insert([
                'date' => date("Y-m-d H:i:s"),
                'integrator_log_id' => $retry_id,
                'duration_in_seconds' => $total_time,
                'response_code' => $http_code,
                'response_body' => $response_body,
            ]);
        }

        return ['status' => $http_code, 'data' => json_decode($response_body)];
    }
}
