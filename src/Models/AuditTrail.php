<?php

namespace Rollswan\AuditTrail\Models;

use Illuminate\Database\Eloquent\Model;
use Rollswan\Uuid\Traits\WithUuid;

class AuditTrail extends Model
{
    use WithUuid;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'user_type',
        'description',
        'route',
        'method_type',
        'ip_address',
        'user_agent'
    ];
}
