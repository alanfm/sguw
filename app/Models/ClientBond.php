<?php

namespace App\Models;

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

    protected $fillable = [
        'description',
        'radgroupcheck_id',
        'priority',
        // value
    ];

    /**
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
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
        return $this->setConnection('radius_db')->belongsTo(Radgroupcheck::class);
    }

    public function scopeSearch(Builder $query, Request $request): array
    {
        $query->with(['radgroupcheck'])
            ->orWhere('description', 'like', '%'.$request->term.'%');

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
