<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class sps_atualizou_dados_pessoais extends Evento {
    private $endpoint = 'contato';
    private $method = 'post';
    private $rules = [
        'codigo',
        'nome',
        'nascimento',
        'telefone',
        'email',
        'sexo',
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
            "cpf" => null,
        ], $data);

        return [
            'codigo' => $data['codigo'],
            'nome' => $data['nome'],
            'dataNascimento' => $data['nascimento'],
            'telefonePrincipal' => $data['telefone'],
            'emailPrincipal' => $data['email'],
            'cpf' => $data['cpf'],
            'sexo' => $data['sexo'],
            'estadoCidade' => [
                'cidade' => $data['cidade'],
                'estado' => $data['estado'],
            ],
        ];
    }
}
