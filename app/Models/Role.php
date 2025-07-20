<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasUlids;
    protected $hidden = ['id', 'pivot'];

    public $timestamps = false;

    public function operators(): BelongsToMany
    {
        return $this->belongsToMany(Operator::class,
            'operator_role',
            'role_id',
            'operator_id');
    }

}
