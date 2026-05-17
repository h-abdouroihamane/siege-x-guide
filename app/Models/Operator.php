<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function squad(): BelongsToMany
    {
        return $this->belongsToMany(
            Squad::class,
            'operator_squad',
            'operator_id',
            'squad_id',
        )->withPivot('rank');
    }

    public function getSquad(): string
    {
        $squad = $this->squad()->first();
        return $squad ? $squad->name : 'Unaffiliated';
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
        $rework = $this->rework()->first();
        return $rework ? $rework->operation : $this->operation()->first();
    }

    public function compareReleaseDate(
        Operator $otherOperator,
        bool $reverse = false,
    ) {
        $r = $reverse ? -1 : 1;

        $operation = $this->getOperation();
        $otherOperation = $otherOperator->getOperation();

        if ($operation->year !== $otherOperation->year) {
            return $r * ($operation->year < $otherOperation->year ? -1 : 1);
        }

        if ($operation->season !== $otherOperation->season) {
            return $r * ($operation->season < $otherOperation->season ? -1 : 1);
        }

        if ($this->side !== $otherOperator->side) {
            return $this->side === 'Attack' ? -1 : 1;
        }

        if ($this->name === $otherOperator->name) {
            return 0;
        }

        return $this->name < $otherOperator->name ? -1 : 1;
    }

    public function addToSquad(string $squadName)
    {
        $newSquad = Squad::firstWhere('name', $squadName);

        $currentSquad = $this->squad->first();
        $hasSquad = !is_null($currentSquad);
        $validNewSquad = !is_null($newSquad);

        if (
            $hasSquad &&
            $validNewSquad &&
            $currentSquad->id === $newSquad->id
        ) {
            return;
        }

        // Detach the current squad and adjust the ranks
        if ($hasSquad) {
            $currentRank = $currentSquad->pivot->rank;

            //Decrease the rank of the operators of the squad that were below them
            DB::table('operator_squad')
                ->where('squad_id', $currentSquad->id)
                ->where('rank', '>', $currentRank)
                ->decrement('rank', 1);

            $this->squad()->detach();
        }

        if ($validNewSquad) {
            $newSquadMaxRank = $hasSquad
                ? DB::table('operator_squad')
                    ->where('squad_id', $newSquad->id)
                    ->max('rank')
                : 0;

            //Insert with the latest rank
            $this->squad()->attach($newSquad->id, [
                'rank' => $newSquadMaxRank + 1,
            ]);
        }

        return;
    }
}
