<?php

namespace App\Models;

use App\Models\Operator;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Squad extends Model
{
    use HasUlids;

    public $timestamps = false;

    public function operators(): BelongsToMany
    {
        return $this->belongsToMany(Operator::class, 'operator_squad', 'squad_id', 'operator_id');
    }
}
