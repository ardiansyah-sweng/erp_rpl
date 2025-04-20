<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerkModel extends Model
{
    protected $table = 'merks';
    protected $fillable = ['merk'];

    public $timestamps = false;

    public static function getMerkAll()
    {
        return self::all();
    }
}
