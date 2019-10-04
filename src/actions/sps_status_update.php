<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class sps_status_update extends Evento {
    private $endpoint = 'evento';
    private $method = 'post';
    private $rules = [
        'codigo',
        'bk_curso',
        'bk_oferta',
        'status',
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
            'tipo' => 88, // Evento Rubeus: Atualização de Status SPS
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'camposPersonalizados' => [
                'statussps_compl' => $data['status'],
            ],
            'descricao' => "<p>O status dessa pessoa na oferta <strong>{$data['bk_oferta']}</strong> foi atualizado para <strong>{$data['status']}</strong></p>",
        ];
    }
}
