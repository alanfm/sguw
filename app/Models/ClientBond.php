<?php

namespace App\Models;

use App\Models\Discentes\GroupRADIUS as DiscentesGroupRADIUS;
use App\Models\Servidores\GroupRADIUS as ServidorGroupRADIUS;
use App\Models\Radius\Radgroupcheck;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ClientBond extends Model
{
    use HasFactory, LogsActivity;

    public const SERVIDORES = 1;
    public const DISCENTES = 2;

    protected $fillable = [
        'server',
        'description',
        'priority',
        'radgroupcheck_id',
    ];

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'server',
                'description',
                'priority',
                'radgroupcheck_id',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:d/m/Y H:i:s',
            'updated_at' => 'datetime:d/m/Y H:i:s',
        ];
    }

    public function radgroupcheck(): BelongsTo
    {
        if ($this->server == self::DISCENTES)
            return $this->setConnection('RADIUS_discente')->belongsTo(DiscentesGroupRADIUS::class, 'radgroupcheck_id');
        else
            return $this->setConnection('RADIUS_servidor')->belongsTo(ServidorGroupRADIUS::class, 'radgroupcheck_id');
    }

    public function scopeSearch(Builder $query, Request $request): array
    {
        $query->with(['radgroupcheck'])
            ->orWhere('description', 'like', '%'.$request->term.'%');
        // $query->where('description', 'like', '%'.$request->term.'%');
        return [
            'count' => $query->count(),
            'data' => $query->orderBy('description', 'ASC')->paginate(env('APP_PAGINATION'))->appends(['term' => $request->term]),
            'page' => $request->page?? 1,
            'termSearch' => $request->term,
        ];
    }

    public function scopeGetForSelect(): Array
    {
        return self::get()->map(function($query) {
            return [
                'id' => $query->id,
                'name' => $query->description
            ];
        })->toArray();
    }
}
