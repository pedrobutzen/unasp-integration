<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class mestrado_selecao_de_candidato_cancelada extends Evento {
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
        return [
            'pessoa' => [
                'codigo' => 'MES - ' . $data['codigo'],
            ],
            'tipo' => 335, //Seleção do Candidato Cancelada (Mestrado)
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'camposPersonalizados' => [
                'statusdeselecao_compl_proc' => null,
            ],
            'descricao' => "
                <p>A seleção do candidato no processo foi cancelada para o curso {$data['bk_oferta']} no processo de Mestrado.</p>",
        ];
    }
}
