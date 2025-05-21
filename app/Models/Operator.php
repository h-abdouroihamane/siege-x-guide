<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasUlids;

    protected $hidden = ['id', 'pivot'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'operator_role');
    }

    public function getRoles() {
        $roles = [];

        foreach ($this->roles()->get() as $r) {
            $roles[] = $r->name;
        }

        return $roles;
    }

    public function squad(): BelongsToMany
    {
        return $this->belongsToMany(Squad::class, 'operator_squad', 'operator_id', 'squad_id');
    }

        public function getSquad(): string {
        $squad = $this->squad()->first();
        return $squad ? $squad->name : "Unaffiliated";
    }

    public function operation(): BelongsTo {
        return $this->belongsTo(Operation::class, 'operation_id', 'id');
    }

    public function getOperation() {
        return $this->operation()->first();
    }
}
