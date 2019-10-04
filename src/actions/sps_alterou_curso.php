<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class sps_alterou_curso extends Evento {
    private $endpoint = 'evento';
    private $method = 'post';
    private $rules = [
        'codigo',
        'bk_curso',
        'bk_oferta',
        'bk_curso_antigo',
        'bk_oferta_antiga',
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
            'tipo' => 76, // Evento Rubeus: Alterou Curso
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'codLocalOferta' => $data['bk_local_oferta'],
            'dadosOportunidade' => [
                'codPessoa' => $data['codigo'],
                'codCurso' => $data['bk_curso_antigo'],
                'codOferta' => $data['bk_oferta_antiga'],
            ],
            'camposPersonalizados' => [
                'inscricaoabandonada' => 0,
            ],
            'descricao' => "<p>Esta pessoa alterou o seu curso de <strong>{$data['bk_curso_antigo']}</strong> para <strong>{$data['bk_oferta_antiga']}</strong>.</p>",
        ];
    }
}
