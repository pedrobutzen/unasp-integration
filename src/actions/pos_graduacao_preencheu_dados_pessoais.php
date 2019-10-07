<?php

namespace unasp;

use unasp\Rubeus;
use unasp\Util\Evento;

class pos_graduacao_preencheu_dados_pessoais extends Evento {
    private $endpoint = 'contato';
    private $method = 'post';
    private $rules = [
        'codigo',
        'bk_curso',
        'bk_oferta',
        'nome',
        'nascimento',
        'email',
        'cpf',
        'cep',
        'estado',
        'cidade',
        'bairro',
        'endereco',
        'numero',
        'telefone',
    ];

    public function __construct() {}

    public function send(array $data) {
        if(!$this->validate($this->rules, $data))
            return;

        $data_person = $this->cast_values_person($data);
        $data_event = $this->cast_values_event($data);
        
        return [Rubeus::call($this->endpoint, $this->method, $data_person), Rubeus::call('evento', 'post', $data_event)];
    }

    public function cast_values_person(array $data) {
        return [
            'codigo' => 'POS - ' . $data['codigo'],
            'nome' => $data['nome'],
            'dataNascimento' => $data['nascimento'],
            'telefonePrincipal' => $data['telefone'],
            'emailPrincipal' => $data['email'],
            'cpf' => $data['cpf'],
            'cep' => $data['cep'],
            'estadoCidade' => [
                'estado' => $data['estado'],
                'cidade' => $data['cidade'],
            ],
            'bairro' => $data['bairro'],
            'endereco' => $data['endereco'],
            'numero' => $data['numero'],
        ];
    }

    public function cast_values_event(array $data) {
        return [
            'pessoa' => [
                'codigo' => 'POS - ' . $data['codigo'],
            ],
            'tipo' => 169, // Evento Rubeus: [Pós-Graduação] Preencheu Dados Pessoais
            'codCurso' => $data['bk_curso'],
            'codOferta' => $data['bk_oferta'],
            'descricao' => "
                <p>Os dados da pessoa foram atualizados.</p>
                <p><strong>Nome:</strong> {$data['nome']}</p>
                <p><strong>Nascimento:</strong> {$data['nascimento']}</p>
                <p><strong>Telefone:</strong> {$data['telefone']}</p>
                <p><strong>E-mail:</strong> {$data['email']}</p>",
        ];
    }
}
