<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class rubeus_novo_contato extends Evento {
    private $endpoint = 'contato';
    private $method = 'post';
    private $rules = [
        'codigo',
        'nome',
        'nascimento',
        'telefone',
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
        return [
            'codigo' => $data['codigo'],
            'nome' => $data['nome'],
            'dataNascimento' => $data['nascimento'],
            'telefonePrincipal' => $data['telefone'],
            'emailPrincipal' => $data['email'],
        ];
    }
}
