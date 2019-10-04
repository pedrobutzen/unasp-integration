<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class sps_compareceu extends Evento {
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
            'tipo' => 86, // Evento Rubeus: Compareceu à Prova
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'camposPersonalizados' => [
                'comparecimento' => 'Compareceu',
            ],
            'descricao' => "<p>O candidato compareceu à prova para o curso {$data['bk_oferta']}, e sua prova já foi enviada para correção.</p>",
        ];
    }
}
