<?php

namespace unasp;

class Integrator {
    public static function queue($action, array $data = []) {
        return self::send($action, $data);
    }

    public static function send($action, array $data = []) {
        $action = new $action();
        
        return $action->send($data);

        // $data = $action->tratar_dados($data);
        // $endpoint = $action::$endpoint;
        // $method = $action->getMethod();

        // return self::call($endpoint, $method, $data);
    }
}
