<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class sps_abandonou_inscricao extends Evento {
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
            'tipo' => 69, // Evento Rubeus: Abandonou Inscrição
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'camposPersonalizados' => [
                'inscricaoabandonada' => 1,
            ],
            'descricao' => "
                <p>Esta pessoa parou no processo de inscrição.</p>
                <p><strong>Curso:</strong> {$data['bk_curso']}</p>
                <p><strong>Oferta:</strong> {$data['bk_oferta']}</p>",
        ];
    }
}
