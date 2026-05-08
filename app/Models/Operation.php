<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Operation extends Model
{
    protected $table = 'operations';
    protected $keyType = 'string';
    protected $hidden = ['id', 'pivot'];
    protected $guarded = [];
    public $timestamps = false;

    public function scopeMostRecent(Builder $query): Builder
    {
        return $query->orderBy('year', 'desc')->orderBy('season', 'desc');
    }

    public function operators(): BelongsToMany
    {
        return $this->belongsToMany(
            Operator::class,
            'operators',
            'id',
            'operation_id',
        );
    }
}
