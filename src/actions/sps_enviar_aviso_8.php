<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class sps_enviar_aviso_8 extends Evento {
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
            'tipo' => 109, // Evento Rubeus: Aviso 8
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'descricao' => "<p>O aviso 8 foi enviado para o lead.</p>",
        ];
    }
}
