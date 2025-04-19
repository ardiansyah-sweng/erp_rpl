namespace App\Http\Controllers;

use App\Models\SupplierPic;
use Illuminate\Http\Request;

class SupplierPIController extends Controller
{
    public function deleteSupplierPICByID($id)
    {
        $deleted = SupplierPic::deleteSupplierPICByID($id);

        if ($deleted) {
            return response()->json(['message' => 'PIC berhasil dihapus.']);
        } else {
            return response()->json(['message' => 'PIC tidak ditemukan.'], 404);
        }
    }
}
