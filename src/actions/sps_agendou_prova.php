<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class sps_agendou_prova extends Evento {
    private $endpoint = 'evento';
    private $method = 'post';
    private $rules = [
        'codigo',
        'bk_curso',
        'bk_oferta',
        'data',
        'hora',
        'local',
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
            'endereco' => null,
        ], $data);

        return [
            'pessoa' => [
                'codigo' => $data['codigo'],
            ],
            'tipo' => 71, // Evento Rubeus: Agendou Prova
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'camposPersonalizados' => [
                'tipodeprova' => 'Prova',
                'datadaprova12_14_39' => $data['data'],
                'horadaprova' => $data['hora'],
                'localdaprova' => $data['local'],
                'enderecodaprova' => $data['endereco'],
                'comparecimento' => 'Pendente',
            ],
            'descricao' => "
                <p>Uma prova foi agendada para esta pessoa.</p>
                <p><strong>Curso:</strong> {$data['bk_curso']}</p>
                <p><strong>Oferta:</strong> {$data['bk_oferta']}</p>
                <p><strong>Local da Prova:</strong> {$data['local']}</p>
                <p><strong>Data da Prova:</strong> {$data['data']} - {$data['hora']}</p>",
        ];
    }
}
