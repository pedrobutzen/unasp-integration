<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class pos_graduacao_iniciou_inscricao extends Evento {
    private $endpoint = 'contato';
    private $method = 'post';
    private $rules = [
        'codigo',
        'nome',
        'email',
    ];

    public function __construct() {}

    public function send(array $data) {
        if(!$this->validate($this->rules, $data))
            return '';

        $data = $this->cast_values($data);
        return Rubeus::call($this->endpoint, $this->method, $data);
    }

    public function cast_values(array $data) {
        $data = array_merge([
            "celular" => null,
        ], $data);

        return [
            'codigo' => 'POS - ' . $data['codigo'],
            'evento' => [
                'tipo' => 175,
            ],
            'nome' => $data['nome'],
            'emailPrincipal' => $data['email'],
            'telefonePrincipal' => $data['celular'],
        ];
    }
}
