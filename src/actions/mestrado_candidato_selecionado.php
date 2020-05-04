<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class mestrado_candidato_selecionado extends Evento {
    private $endpoint = 'evento';
    private $method = 'post';
    private $rules = [
        'codigo',
        'status_selecao',
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
        return [
            'pessoa' => [
                'codigo' => 'MES - ' . $data['codigo'],
            ],
            'tipo' => 332, //Candidato Selecionado (Mestrado)
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'camposPersonalizados' => [
                'statusdeselecao_compl_proc' => $data['status_selecao'],
            ],
            'descricao' => "
                <p>Essa pessoa foi selecionada como aluno {$data['status_selecao']} no processo para o curso {$data['bk_oferta']} no processo de Mestrado.</p>",
        ];
    }
}
