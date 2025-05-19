<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasUlids;

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'operator_role');
    }

    public function squad(): BelongsToMany
    {
        return $this->belongsToMany(Squad::class, 'operator_squad', 'operator_id', 'squad_id');
    }
}
