<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class sps_enviar_aviso_3 extends Evento {
    private $endpoint = 'evento';
    private $method = 'post';
    private $rules = [
        'codigo',
    ];

    public function __construct() {}

    public function send(array $data) {
        if(!$this->validate($this->rules, $data))
            return;

        $data = $this->cast_values($data);

        return Rubeus::call($this->endpoint, $this->method, $data);
    }

    public function cast_values(array $data) {
        $data = array_merge([
            "bk_curso" => null,
            "bk_oferta" => null,
        ], $data);

        return [
            'pessoa' => [
                'codigo' => $data['codigo'],
            ],
            'tipo' => 111, // Evento Rubeus: Aviso 3
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'descricao' => "<p>O aviso 3 foi enviado para o lead.</p>",
        ];
    }
}
