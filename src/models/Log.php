<?php

namespace unasp\Models;

use Eloquent as Model;

class Log extends Model
{
    public $table = 'integrator_log';

    protected $casts = [
        'id' => 'integer',
        'date' => 'datetime',
        'api' => 'string',
        'url' => 'string',
        'method' => 'string',
        'request' => 'string',
        'duration_in_seconds' => 'integer',
        'response_code' => 'integer',
        'response_body' => 'string',
    ];

    public function retryLogs()
    {
        return $this->hasMany(RetryLog::class, 'integrator_log_id', 'id');
    }
}
