<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class sps_iniciou_inscricao_teologia extends Evento {
    private $endpoint = 'evento';
    private $method = 'post';
    private $rules = [
        'codigo',
        'bk_processo',
    ];

    public function __construct() {}

    public function send(array $data) {
        if(!$this->validate($this->rules, $data))
            return;

        $data = $this->cast_values($data);

        return Rubeus::call($this->endpoint, $this->method, $data);
    }

    public function cast_values(array $data) {
        return [
            'pessoa' => [
                'codigo' => $data['codigo'],
            ],
            'tipo' => 361, // Evento Rubeus: Iniciou Inscrição (Teologia)
            'descricao' => "<strong>Curso:</strong> Teologia - Bacharelado <br><strong>Processo Seletivo:</strong> {$data['bk_processo']} <br>",
        ];
    }
}
