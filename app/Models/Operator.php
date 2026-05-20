<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory, HasUlids;

    protected $hidden = ['id', 'pivot'];
    protected $guarded = ['id'];
    public $timestamps = false;

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'operator_role');
    }

    public function getRoles()
    {
        $roles = [];

        foreach ($this->roles()->get() as $r) {
            $roles[] = $r->name;
        }

        return $roles;
    }

    public function operation(): BelongsTo
    {
        return $this->belongsTo(Operation::class, 'operation_id', 'id');
    }

    public function queerIdentities()
    {
        return $this->belongsToMany(
            QueerIdentity::class,
            'operator_queer_identity',
            'operator_id',
            'queer_identity_id',
        );
    }

    public function secondaryGadgets()
    {
        return $this->belongsToMany(
            SecondaryGadget::class,
            'operator_secondary_gadget',
            'operator_id',
            'secondary_gadget_id',
        );
    }

    public function rework()
    {
        return $this->hasOne(Rework::class, 'operator_id', 'id');
    }

    public function getOperation()
    {
        // Property access (not ->rework()/->operation()) so eager-loaded
        // relations are reused instead of firing a query per operator.
        return $this->rework ? $this->rework->operation : $this->operation;
    }

    public function compareReleaseDate(
        Operator $otherOperator,
        bool $reverse = false,
    ) {
        $r = $reverse ? -1 : 1;

        $operation = $this->getOperation();
        $otherOperation = $otherOperator->getOperation();

        // Fall back to the operator's own year/season if the operation
        // is missing, so sorting never dereferences null.
        $year = $operation?->year ?? $this->year;
        $season = $operation?->season ?? $this->season;
        $otherYear = $otherOperation?->year ?? $otherOperator->year;
        $otherSeason = $otherOperation?->season ?? $otherOperator->season;

        if ($year !== $otherYear) {
            return $r * ($year < $otherYear ? -1 : 1);
        }

        if ($season !== $otherSeason) {
            return $r * ($season < $otherSeason ? -1 : 1);
        }

        if ($this->side !== $otherOperator->side) {
            return $this->side === 'Attack' ? -1 : 1;
        }

        if ($this->name === $otherOperator->name) {
            return 0;
        }

        return $this->name < $otherOperator->name ? -1 : 1;
    }
}
