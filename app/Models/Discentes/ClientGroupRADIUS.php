<?php

namespace App\Models\Discentes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientGroupRADIUS extends Model
{
    use HasFactory;

    /**
     * Set connection database server
     */
    protected $connection = "RADIUS_discente";

    /**
     * Set database table
     */
    protected $table = "radusergroup";

    /**
     * Fillable fields
     */
    protected $fillable = [
        'username',
        'groupname',
        'priority',
    ];

    /**
     * Set timestamps with false
     */
    public $timestamps = false;
}