<?php

namespace unasp\Models;

use Eloquent as Model;

class Queue extends Model
{
    public $table = 'integrator_queue';

    public $timestamps = false;

    protected $casts = [
        'id' => 'integer',
        'date' => 'datetime',
        'action' => 'string',
        'data' => 'string',
        'blocked' => 'boolean',
    ];

    public function scopeBlocked($query) {
        return $query->where('blocked', 1);
    }

    public function scopeUnlocked($query) {
        return $query->where('blocked', 0);
    }

    public function getCrmCodigoAttribute() {
        return explode("}", explode(",", explode("\"codigo\":", $this->data)[1])[0])[0];
    }

    public function unlock() {
        $this->blocked = false;
        $this->save();
    }
}
