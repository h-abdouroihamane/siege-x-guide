<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
class QueerIdentity extends Model
{
    use HasUlids;
    protected $hidden = ['id', 'pivot'];
    public $timestamps = false;
}
