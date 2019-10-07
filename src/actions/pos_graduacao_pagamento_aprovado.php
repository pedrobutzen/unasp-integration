<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class pos_graduacao_pagamento_aprovado extends Evento {
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
        $data = array_merge([
            "metodo" => null,
        ], $data);

        return [
            'pessoa' => [
                'codigo' => 'POS - ' . $data['codigo'],
            ],
            'tipo' => 170, // Evento Rubeus: [Pós-Graduação] Pagamento Aprovado
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'camposPersonalizados' => [
                'formadepagamento_compl' => $data['metodo'],
            ],
            'descricao' => "<p><p>Pagamento aprovado para o curso {$data['bk_oferta']} no processo de Pós-Graduação.</p>",
        ];
    }
}
