<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class mestrado_gerou_pagamento extends Evento {
    private $endpoint = 'evento';
    private $method = 'post';
    private $rules = [
        'codigo',
        'bk_curso',
        'bk_oferta',
    ];

    public function __construct() {}

    public function send(array $data) {
        if(!$this->validate($this->rules, $data))
            return '';

        $data = $this->cast_values($data);
        return Rubeus::call($this->endpoint, $this->method, $data);
    }

    public function cast_values(array $data) {
        return [
            'pessoa' => [
                'codigo' => 'MES - ' . $data['codigo'],
            ],
            'tipo' => 327, //Gerou Pagamento (Mestrado)
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'descricao' => "
                <p>Pagamento gerado para o curso {$data['bk_oferta']} no processo de Mestrado.</p>",
        ];
    }
}
