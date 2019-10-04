<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class sps_escolheu_curso extends Evento {
    private $endpoint = 'evento';
    private $method = 'post';
    private $rules = [
        'codigo',
        'inscricao',
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
        $data = array_merge([
            "bk_local_oferta" => null,
        ], $data);

        return [
            'pessoa' => [
                'codigo' => $data['codigo'],
            ],
            'tipo' => 68, // Evento Rubeus: Escolheu Curso
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'codLocalOferta' => $data['bk_local_oferta'],
            'dadosOportunidade' => [
                'codPessoa' => $data['codigo'],
            ],
            'camposPersonalizados' => [
                'inscricaoabandonada' => 0,
                'ndeinscricaonosps' => $data['inscricao'],
            ],
            'descricao' => "<p>Esta pessoa escolheu o curso <strong>{$data['bk_oferta']}</strong>.</p>",
        ];
    }
}
