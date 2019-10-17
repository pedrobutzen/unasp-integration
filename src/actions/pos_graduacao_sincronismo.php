<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class pos_graduacao_sincronismo extends Evento {
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
            "metodo" => null,
            "documentos_irregulares" => null,
            "documentos_irregulares_html" => null,
        ], $data);

        return array_filter([
            'api' => true,
            'pessoa' => [
                'codigo' => $data['codigo'],
            ],
            'tipo' => $data['tipo'],
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'camposPersonalizados' => array_filter([
                'formadepagamento_compl' => $data['metodo'],
                'pos-graduacaodocumentosfaltando_compl' => $data['documentos_irregulares'],
                'pos-graduacaodocumentosfaltandohtml_compl' => $data['documentos_irregulares_html'],
            ]),
            'descricao' => $data['descricao'],
        ]);
    }
}
