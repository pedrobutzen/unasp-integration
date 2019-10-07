<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class pos_graduacao_documentos_irregulares extends Evento {
    private $endpoint = 'evento';
    private $method = 'post';
    private $rules = [
        'codigo',
        'documentos',
        'documentos_html',
    ];

    public function __construct() {}

    public function send(array $data) {
        if(!$this->validate($this->rules, $data))
            return '';

        $data_person = $this->cast_values_person($data);
        $data_event = $this->cast_values_event($data);
        
        return [Rubeus::call($this->endpoint, $this->method, $data_person), Rubeus::call('evento', 'post', $data_event)];
    }

    public function cast_values_person(array $data) {
        return [
            'codigo' => 'POS - ' . $data['codigo'],
            'camposPersonalizados' => [
                'pos-graduacaodocumentosfaltando_compl' => $data['documentos'],
                'pos-graduacaodocumentosfaltandohtml_compl' => $data['documentos_html'],
            ],
        ];
    }

    public function cast_values_event(array $data) {
        return [
            'pessoa' => [
                'codigo' => 'POS - ' . $data['codigo'],
            ],
            'tipo' => 167, // Evento Rubeus: [Pós-Graduação] Identificadas irregularidades nos documentos
            'descricao' => "
                <p>Há documentos obrigatórios faltando na matrícula da Pós-Graduação!</p>
                <br />
                {$data['documentos_html']}",
        ];
    }
}
