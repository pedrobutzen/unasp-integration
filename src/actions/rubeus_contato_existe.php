<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class rubeus_contato_existe extends Evento {
    private $endpoint = 'dados_contato';
    private $method = 'post';
    private $rules = [
        'codigo',
    ];

    public function __construct() {}

    public function send(array $data) {
        if(!$this->validate($this->rules, $data))
            return;

        $data = $this->cast_values($data);
        $return = Rubeus::call($this->endpoint, $this->method, $data);
        return $return['data']->success;
    }

    public function cast_values(array $data) {
        return [
            'codigo' => $data['codigo'],
        ];
    }
}
