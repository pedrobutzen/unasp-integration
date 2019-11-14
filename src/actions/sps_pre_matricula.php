<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class sps_pre_matricula extends Evento {
    private $endpoint = 'evento';
    private $method = 'post';
    private $rules = [
        'codigo',
        'tipo',
        'bk_curso',
        'bk_oferta',
        'descricao',
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
            'tipo' => $data['tipo'],
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'descricao' => "<p>{$data['descricao']} <br>Curso <strong>{$data['bk_oferta']}</strong></p>",
        ];
    }
}
