<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
class Rework extends Model
{
    protected $hidden = ['id', 'pivot'];
    protected $table = 'operator_rework';
    public $timestamps = false;

    public function operation(): BelongsTo {
        return $this->belongsTo(Operation::class);
    }

    public function operator(): BelongsTo {
        return $this->belongsTo(Operator::class);
    }
}
