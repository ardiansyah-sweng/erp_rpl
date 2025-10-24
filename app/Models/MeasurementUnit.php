<?php
namespace  App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeasurementUnit extends Model
{
    use HasFactory;

    // Menentukan nama tabel secara eksplisit agar konsisten dengan migrasi
        protected $table = 'measurement_unit';

            // Izinkan mass assignment untuk kolom-kolom ini
                protected $fillable = ['unit_name', 'unit_symbol'];
}
