<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class sps_solicitou_prova_online extends Evento {
    private $endpoint = 'evento';
    private $method = 'post';
    private $rules = [
        'codigo',
        'ano_formacao',
        'url_prova',
        'num_inscricao',
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
                'anoFormacao' => $data['ano_formacao'],
            ],
            'tipo' => 315, // Evento Rubeus: Solicitou Prova Online
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'camposPersonalizados' => [
                'tipodeprova' => 'Online',
                'urlprovaonline1_compl_proc' => $data['url_prova'],
                'ndeinscricaonosps' => $data['num_inscricao'],

                'datadaprova12_14_39' => null,
                'horadaprova' => null,
                'localdaprova' => null,
                'enderecodaprova' => null,
                'comparecimento' => null,
                'numerodoenem' => null,
            ],
            'descricao' => "<p>O candidato solicitou prova online para o curso {$data['bk_oferta']}</p>",
        ];
    }
}
