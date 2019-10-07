<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class pos_graduacao_escolheu_curso extends Evento {
    private $endpoint = 'evento';
    private $method = 'post';
    private $rules = [
        'codigo',
        'bk_curso',
        'bk_oferta',
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
            'pessoa' => [
                'codigo' => 'POS - ' . $data['codigo'],
            ],
            'tipo' => 168, // Evento Rubeus: [Pós-Graduação] Escolheu Curso
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'descricao' => "
                <p>Esta pessoa escolheu o curso da pós-graduação.</p>
                <p><strong>Curso:</strong> {$data['bk_oferta']}</p>",
        ];
    }
}
