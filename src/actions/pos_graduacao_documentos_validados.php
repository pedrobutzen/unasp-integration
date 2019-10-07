<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class pos_graduacao_documentos_validados extends Evento {
    private $endpoint = 'evento';
    private $method = 'post';
    private $rules = [
        'codigo',
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
                'pos-graduacaodocumentosfaltando_compl' => [],
                'pos-graduacaodocumentosfaltandohtml_compl' => '',
            ],
        ];
    }

    public function cast_values_event(array $data) {
        return [
            'pessoa' => [
                'codigo' => 'POS - ' . $data['codigo'],
            ],
            'tipo' => 171, // Evento Rubeus: [Pós-Graduação] Documentos validados
            'descricao' => "<p>Todos os documentos da Pós-Graduação foram validados!</p>",
        ];
    }
}
