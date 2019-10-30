<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class pos_graduacao_alterou_curso extends Evento {
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
            return '';

        $data = $this->cast_values($data);
        return Rubeus::call($this->endpoint, $this->method, $data);
    }

    public function cast_values(array $data) {
        return [
            'pessoa' => [
                'codigo' => 'POS - ' . $data['codigo'],
            ],
            'tipo' => 256, // Evento Rubeus: [Pós-Graduação] Alterou Curso
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'dadosOportunidade' => [
                'codPessoa' => 'POS - ' . $data['codigo'],
                'codCurso' => $data['bk_curso_antigo'],
                'codOferta' => $data['bk_oferta_antiga'],
            ],
            'descricao' => "<p>Esta pessoa alterou o seu curso de <strong>{$data['bk_oferta_antiga']}</strong> para <strong>{$data['bk_oferta']}</strong>.</p>",
        ];
    }
}
