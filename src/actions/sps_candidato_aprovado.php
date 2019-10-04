<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class sps_candidato_aprovado extends Evento {
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
            return;

        $data = $this->cast_values($data);

        return Rubeus::call($this->endpoint, $this->method, $data);
    }

    public function cast_values(array $data) {
        return [
            'pessoa' => [
                'codigo' => $data['codigo'],
            ],
            'tipo' => 63, //Evento Rubeus: Candidato Aprovado
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'descricao' => "<p>O candidato foi aprovado para o curso {$data['bk_oferta']}.</p>",
        ];
    }
}
