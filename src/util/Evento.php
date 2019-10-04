<?php

namespace unasp\Util;

use Exception;

class Evento {
    public function validate(array $rules, array $data) {
        foreach ($rules as $rule) {
            if(!array_key_exists($rule, $data)) {
                return false;
            }
        }
        return true;
    }
}
