<?php

namespace unasp;

class Integrator {
    public static function queue($action, array $data = []) {
        return self::send($action, $data);
    }

    public static function send($action, array $data = []) {
        $action = "unasp\\$action";

        if(!class_exists($action))
            return "Evento {$action} não existe.";

        $action = new $action();

        return $action->send($data);
    }
}
