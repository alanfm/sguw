<?php

namespace App\Models\Discentes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientRADIUS extends Model
{
    use HasFactory;

    /**
     * Set connection database server
     */
    protected $connection = "RADIUS_discente";

    /**
     * Set database table
     */
    protected $table = "radcheck";

    /**
     * Fillable fields
     */
    protected $fillable = [
        'username',
        'attribute',
        'op',
        'value',
    ];

    /**
     * Set timestamps with false
     */
    public $timestamps = false;
}
