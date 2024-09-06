<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ClientUpload extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'file',
        'keep_clients', // 1 para sim, 2 para não
        'user_id',
        'client_bond_id',
        'observations'
    ];

    public function casts(): array{
        return [
            'created_at' => 'date:d/m/Y H:i:s',
            'updated_at' => 'date:d/m/Y H:i:s',
        ];
    }

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'file',
                'keep_clients',
                'user.name',
                'client_bond.description'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function client_bond(): BelongsTo
    {
        return $this->belongsTo(ClientBond::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch(Builder $query, Request $request): array
    {
        $query->with(['user', 'client_bond'])
        ->whereHas('user', function(Builder $query) use ($request) {
            $query->where('name', 'like', "%{$request->term}%");
        })->orWhereHas('client_bond', function(Builder $query) use ($request) {
            $query->where('description', 'like', "%{$request->term}%");
        });

        return [
            'count' => $query->count(),
            'data' => $query->orderBy('created_at', 'DESC')->paginate(env('APP_PAGINATION'))->appends(['term' => $request->term]),
            'page' => $request->page?? 1,
            'termSearch' => $request->term,
        ];
    }
}
