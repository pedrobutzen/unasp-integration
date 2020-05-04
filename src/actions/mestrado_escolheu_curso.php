<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class mestrado_escolheu_curso extends Evento {
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
            'tipo' => 324, //Escolheu Curso (Mestrado)
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'descricao' => "
                <p>Candidato escolheu o curso do mestrado</p>
                <p><strong>Curso:</strong> {$data['bk_oferta']}</p>",
        ];
    }
}
