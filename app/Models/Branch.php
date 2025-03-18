<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'branches';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'branch_name',
        'branch_address',
        'branch_telephone',
        'branch_status',
    ];

    /**
     * Fungsi untuk menambahkan branch baru dengan validasi.
     */
    public static function addBranch($data)
    {
        // Validasi: Nama branch tidak boleh kosong & harus lebih dari 3 karakter
        if (empty($data['branch_name']) || strlen($data['branch_name']) < 3) {
            return ['error' => 'Nama branch harus diisi dan minimal 3 karakter'];
        }

        // Cek apakah nama branch sudah ada di database
        if (self::where('branch_name', $data['branch_name'])->exists()) {
            return ['error' => 'Nama branch sudah digunakan'];
        }

        // Jika lolos validasi, buat branch baru
        return self::create($data);
    }
}
