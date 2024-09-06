<?php

namespace App\Models\Servidores;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupRADIUS extends Model
{
    use HasFactory;

    /**
     * Set connection database server
     */
    protected $connection = "RADIUS_servidor";

    /**
     * Set database table
     */
    protected $table = "radgroupcheck";

    /**
     * Fillable fields
     */
    protected $fillable = [
        'groupname',
        'attribute',
        'op',
        'value'
    ];

    /**
     * Set timestamps with false
     */
    public $timestamps = false;
}
