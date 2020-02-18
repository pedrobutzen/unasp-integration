<?php

namespace unasp;

use DB;

class Integrator {
    public static function queue($action, array $data = []) {
        if(!class_exists("unasp\\$action")) {
            DB::table('integrator_queue_failed')->insert([
                'date' => date("Y-m-d H:i:s"),
                'action' => $action,
                'data' => json_encode($data),
                'response' => "Evento {$action} não existe.",
            ]);
            return false;
        }

        $blocked = array_key_exists('blocked', $data) ? $data['blocked'] : false;
        if(array_key_exists('blocked', $data))
            unset($data['blocked']);

        $integrator_queue_id = DB::table('integrator_queue')->insertGetId([
            'date' => date("Y-m-d H:i:s"),
            'action' => $action,
            'data' => json_encode($data),
            'blocked' => $blocked,
        ]);

        return true;
    }

    public static function send($action, array $data = []) {
        $action = "unasp\\$action";

        if(!class_exists($action))
            return "Evento {$action} não existe.";

        $action = new $action();

        return $action->send($data);
    }
}
