<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Operation extends Model
{
    use HasFactory;

    protected $table = 'operations';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $hidden = ['id', 'pivot'];
    protected $guarded = [];
    public $timestamps = false;

    protected function casts(): array
    {
        // Cast to a Carbon date but keep the Y-m-d serialization the
        // frontend already consumes (no API shape change).
        return ['release_date' => 'date:Y-m-d'];
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
