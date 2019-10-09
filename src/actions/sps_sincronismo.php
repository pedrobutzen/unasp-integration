<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class sps_sincronismo extends Evento {
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
        $data = array_merge([
            "data_do_evento" => null,
            "tipo_do_evento" => null,
            "data" => null,
            "hora" => null,
            "local" => null,
            "endereco" => null,
            "comparecimento" => null,
            "num_inscricao" => null,
            "status" => null,
            "tipo_de_avaliacao" => null,
            "numero_enem" => null,
            "cupom" => null,
        ], $data);

        return array_filter([
            'pessoa' => [
                'codigo' => $data['codigo'],
            ],
            'tipo' => $data['tipo'],
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'data' => $data['data_do_evento'],
            'tipoData' => $data['tipo_do_evento'],
            'camposPersonalizados' => array_filter([
                'datadaprova12_14_39' => $data['data'],
                'horadaprova' => $data['hora'],
                'localdaprova' => $data['local'],
                'enderecodaprova' => $data['endereco'],
                'comparecimento' => $data['comparecimento'],
                'ndeinscricaonosps' => $data['num_inscricao'],
                'statussps_compl' => $data['status'],
                'tipodeprova' => $data['tipo_de_avaliacao'],
                'numerodoenem' => $data['numero_enem'],
                'ultimocupom' => $data['cupom'],
            ]),
            'descricao' => $data['descricao'],
        ]);
    }
}
