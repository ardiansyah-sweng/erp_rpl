<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table;
    protected $fillable = ['company_name', 'address','phone_number'];

    protected $primaryKey = 'supplier_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('db_constants.table.supplier');
        $this->fillable = array_values(config('db_constants.column.supplier') ?? []);
    }

    public static function getUpdateSupplier($supplier_id, array $data)
    {
        $supplier = self::find($supplier_id);
        if (!$supplier) {
            return null;
        }

        $fillable = (new self)->getFillable();
        $filteredData = array_intersect_key($data, array_flip($fillable));
        $supplier->update($filteredData);

        return $supplier;
    }
    public static function countSupplier() {

        //testbro
        $query = "SELECT COUNT(*) AS total FROM supplier";
        $result = mysqli_query($conn, $query);
    
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['total'];
        } else {
            return 0; // atau tampilkan pesan error
        }
    }


}