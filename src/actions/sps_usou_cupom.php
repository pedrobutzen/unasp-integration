<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class sps_usou_cupom extends Evento {
    private $endpoint = 'evento';
    private $method = 'post';
    private $rules = [
        'codigo',
        'bk_curso',
        'bk_oferta',
        'cupom',
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
            'tipo' => 62, //Evento Rubeus: Usou Cupom
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'camposPersonalizados' => [
                'inscricaoabandonada' => 0,
                'ultimocupom' => $data['cupom'],
            ],
            'descricao' => "
                <p><strong>Curso:</strong> {$data['bk_curso']}</p>
                <p><strong>Oferta:</strong> {$data['bk_oferta']}</p>
                <p><strong>Cupom:</strong> {$data['cupom']}</p>",
        ];
    }
}
