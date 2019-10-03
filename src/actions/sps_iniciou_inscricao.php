<?php

namespace unasp;

use Rubeus;

class sps_iniciou_inscricao {
    public static $endpoint = 'evento';
    public static $method = 'post';

    public function send(array $data = []) {
        self::validate($data);

        $data = self::cast_values($data);

        return Rubeus::call(self::$endpoint, self::$method, $data);
    }

    public static function validate(array $data) {
        return true;
    }

    public static function cast_values(array $data) {
        return [
            'pessoa' => [
                'codigo' => $data['codigo'],
            ],
            'tipo' => 60, // Evento Rubeus: Iniciou Inscrição
            'descricao' =>
                "<strong>Curso:</strong> ainda não decidido <br>
                <strong>Processo Seletivo:</strong> {$data['bk_processo»']} <br>",
        ];
    }
}
