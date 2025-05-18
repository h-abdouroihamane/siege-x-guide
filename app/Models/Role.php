<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Role extends Model
{
    use HasUlids;
public $timestamps = false;
}
