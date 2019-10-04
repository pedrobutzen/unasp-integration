<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class sps_preencheu_formulario extends Evento {
    private $endpoint = 'contato';
    private $method = 'post';
    private $rules = [
        'codigo',
        'bk_curso',
        'bk_oferta',
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

        $data_person = $this->cast_values_person($data);
        Rubeus::call($this->endpoint, $this->method, $data_person);

        $data_event = $this->cast_values_event($data);
        Rubeus::call('evento', 'post', $data_event);
    }

    public function cast_values_person(array $data) {
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

    public function cast_values_event(array $data) {
        return [
            'pessoa' => [
                'codigo' => $data['codigo'],
            ],
            'tipo' => 70, // Evento Rubeus: Preencheu Formulário de Dados
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'camposPersonalizados' => [
                'inscricaoabandonada' => 0,
            ],
            'descricao' => "
                <p>Os dados da pessoa foram atualizados.</p>
                <p><strong>Nome:</strong> {$data['nome']}</p>
                <p><strong>Nascimento:</strong> {$data['nascimento']}</p>
                <p><strong>Telefone:</strong> {$data['telefone']}</p>
                <p><strong>E-mail:</strong> {$data['email']}</p>
                <p><strong>Oferta:</strong> {$data['bk_oferta']}</p>",
        ];
    }
}
