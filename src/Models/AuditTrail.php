<?php

namespace Rollswan\AuditTrail\Models;

use Illuminate\Database\Eloquent\Model;
use Rollswan\Uuid\Traits\WithUuid;
use Rollswan\AuditTrail\Traits\IpAddressChecker;
use Rollswan\AuditTrail\Traits\UserAgentChecker;

class AuditTrail extends Model
{
    use WithUuid, UserAgentChecker, IpAddressChecker;

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

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'platform',
        'browser',
        'version',
        'ip_address_location'
    ];

    /**
     * Returns activity log platform.
     *
     * @return string
     */
    public function getPlatformAttribute()
    {
        return $this->getUserAgentInfo($this->user_agent)["platform"] ?? '';
    }

    /**
     * Returns activity log browser.
     *
     * @return string
     */
    public function getBrowserAttribute()
    {
        return $this->getUserAgentInfo($this->user_agent)["browser"] ?? '';
    }

    /**
     * Returns activity log version.
     *
     * @return string
     */
    public function getVersionAttribute()
    {
        return $this->getUserAgentInfo($this->user_agent)["version"] ?? '';
    }

    /**
     * Returns activity log ip address location details.
     *
     * @return string
     */
    public function getIpAddressLocationAttribute()
    {
        return $this->getIpAddressLocation($this->ip_address);
    }
}
