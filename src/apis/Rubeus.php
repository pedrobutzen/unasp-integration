<?php

namespace unasp;

use Config;

class Rubeus {
    public static $invokeURL = "https://crmunasp.rubeus.com.br/api";
    
    public static $endpoints = [
        "dados_contato" => "/Contato/dadosPessoa",
        "contato" => "/Contato/cadastro",
        "evento" => "/Evento/cadastro",
    ];

    public static function queue($action, array $data = []) {
        return self::send($action, $data);
    }

    public static function send($action, array $data = []) {
        return self::call();
    }

    private static function call(string $endpoint, $method, array $data = []) {
        $data = array_merge($data, [
            "origem" => 5, # Processo Seletivo Próprio
            "token" => Config::get('unasp_integrations.RUBEUS_TOKEN'),
        ]);

        $ch = curl_init();

        if ($ch === false) {
            throw new Exception('Error on cURL initialization.');
        }
        
        curl_setopt_array($ch, [
            CURLOPT_URL => self::$invokeURL . self::$endpoints[$endpoint],
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
            ],
            CURLOPT_POST => $method == 'post',
            CURLOPT_POSTFIELDS => json_encode($data),
        ]);

        $data = curl_exec($ch);

        if ($data === false) {
            throw new Exception(curl_error($ch), curl_errno($ch));
        }

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);

        return ['status' => $http_code, 'data' => json_decode($data)];
    }
}

