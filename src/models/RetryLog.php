<?php

namespace unasp\Models;

use Eloquent as Model;

class RetryLog extends Model
{
    public $table = 'retry_integrator_log';

    protected $casts = [
        'id' => 'integer',
        'date' => 'datetime',
        'integrator_log_id' => 'integer',
        'duration_in_seconds' => 'integer',
        'response_code' => 'integer',
        'response_body' => 'string',
    ];

    public function log()
    {
        return $this->belongsTo(Log::class);
    }
}
