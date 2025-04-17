<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerkModel extends Model
{
    protected $table = 'merks'; 

    public function getMerk()
    {
        return self::all();
    }
}
