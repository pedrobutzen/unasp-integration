<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class sps_solicitou_enem extends Evento {
    private $endpoint = 'evento';
    private $method = 'post';
    private $rules = [
        'codigo',
        'bk_curso',
        'bk_oferta',
        'numero',
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
            'tipo' => 72, // Evento Rubeus: Solicitou Avaliação do ENEM
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'camposPersonalizados' => [
                'tipodeprova' => 'ENEM',
                'numerodoenem' => $data['numero'],
                'datadaprova12_14_39' => null,
                'horadaprova' => null,
                'localdaprova' => null,
                'enderecodaprova' => null,
                'comparecimento' => null,
            ],
            'descricao' => "
                <p>Essa pessoa enviou o número do ENEM para ser avaliado.</p>
                <p><strong>Curso:</strong> {$data['bk_curso']}</p>
                <p><strong>Oferta:</strong> {$data['bk_oferta']}</p>
                <p><strong>Número ENEM:</strong> {$data['numero']}</p>",
        ];
    }
}
