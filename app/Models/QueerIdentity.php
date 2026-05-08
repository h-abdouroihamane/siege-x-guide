<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueerIdentity extends Model
{
    use HasFactory, HasUlids;
    protected $hidden = ['id', 'pivot'];
    public $timestamps = false;
}
