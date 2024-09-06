<?php

namespace App\Models\Discentes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class DeviceRADIUS extends Model
{
    use HasFactory;

    /**
     * Set connection database server
     */
    protected $connection = "RADIUS_discente";

    /**
     * Set database table
     */
    protected $table = "nas";

    /**
     * Fillable fields
     */
    protected $fillable = [
        'nasname',
        'shortname',
        'type',
        'ports',
        'secret',
        'server',
        'community',
        'description',
    ];

    /**
     * Set timestamps with false
     */
    public $timestamps = false;

    public function scopeSearch(Builder $query, Request $request): array
    {
        $query->where('shortname', 'like', '%'.$request->term.'%')
            ->orWhere('description', 'like', '%'.$request->term.'%')
            ->orWhere('nasname', 'like', '%'.$request->term.'%');

        return [
            'count' => $query->count(),
            'data' => $query->orderBy('shortname', 'ASC')->paginate(env('APP_PAGINATION'))->appends(['term' => $request->term]),
            'page' => $request->page?? 1,
            'termSearch' => $request->term,
        ];
    }
}
