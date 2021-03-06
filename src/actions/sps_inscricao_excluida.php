<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class sps_inscricao_excluida extends Evento {
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
            'tipo' => 96, // Evento Rubeus: Excluída
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'camposPersonalizados' => [
                'statussps_compl' => 'Excluída',
            ],
            'descricao' => "A inscrição foi excluída.<br><strong>Curso:</strong> {$data['bk_curso']}<br><strong>Oferta:</strong> {$data['bk_oferta']}<br>",
        ];
    }
}
