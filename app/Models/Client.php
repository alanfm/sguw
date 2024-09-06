<?php

namespace App\Models;

use App\Models\Servidores\ClientRADIUS as ClientRADServidores;
use App\Models\Discentes\ClientRADIUS as ClientRADDiscentes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Client extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'registry', // Matricula
        'cpf',
        'birth',
        'email',
        'radcheck_id',
        'client_bond_id',
        'observations',
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
                'name',
                'registry', // Matricula
                'cpf',
                'birth',
                'email',
                'radcheck_id',
                'client_bond.description',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function client_bond(): BelongsTo
    {
        return $this->belongsTo(ClientBond::class);
    }

    public function clientRadServidores(): BelongsTo
    {
        return $this->setConnection('RADIUS_servidor')->belongsTo(ClientRADServidores::class);
    }

    public function clientRadDiscentes(): BelongsTo
    {
        return $this->setConnection('RADIUS_discente')->belongsTo(ClientRADDiscentes::class);
    }

    public function scopeSearch(Builder $query, Request $request): array
    {
        $query->with(['client_bond'])
            ->where('name', 'like', '%'.$request->term.'%')
            ->orWhere('cpf', 'like', '%'.$request->term.'%')
            ->orWhere('registry', 'like', '%'.$request->term.'%');

        return [
            'count' => $query->count(),
            'data' => $query->orderBy('name', 'ASC')->paginate(env('APP_PAGINATION'))->appends(['term' => $request->term]),
            'page' => $request->page?? 1,
            'termSearch' => $request->term,
        ];
    }
}
