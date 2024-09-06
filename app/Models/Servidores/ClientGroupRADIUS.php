<?php

namespace App\Models\Servidores;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientGroupRADIUS extends Model
{
    use HasFactory;

    /**
     * Set connection database server
     */
    protected $connection = "RADIUS_servidor";

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
