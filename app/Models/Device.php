<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use App\Models\Servidores\DeviceRADIUS as DeviceRADServidores;
use App\Models\Discentes\DeviceRADIUS as DeviceRADDiscentes;

class Device extends Model
{
    use HasFactory, LogsActivity;

    public const RADIUS_servidor = 'servidor';

    public const RADIUS_discente = 'discente';

    protected $fillable = [
        'description',
        'server'
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:d/m/Y H:i:s',
            'updated_at' => 'datetime:d/m/Y H:i:s',
        ];
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'description',
                'server'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function nasRadServidores(): BelongsTo
    {
        return $this->setConnection('RADIUS_servidor')->belongsTo(DeviceRADServidores::class);
    }

    public function nasRadDiscentes(): BelongsTo
    {
        return $this->setConnection('RADIUS_discente')->belongsTo(DeviceRADDiscentes::class);
    }

    public function scopeSearch(Builder $query, Request $request): array
    {
        $query->with(['nasRadDiscentes', 'nasRadServidores'])
            ->where('description', 'like', '%'.$request->term.'%')
            ->orWhere('server', 'like', '%'.$request->term.'%');

        return [
            'count' => $query->count(),
            'data' => $query->orderBy('description', 'ASC')->paginate(env('APP_PAGINATION'))->appends(['term' => $request->term]),
            'page' => $request->page?? 1,
            'termSearch' => $request->term,
        ];
    }
}
