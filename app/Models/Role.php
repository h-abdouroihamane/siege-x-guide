<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasUlids;
    protected $hidden = ['id', 'pivot'];

    public $timestamps = false;
}
