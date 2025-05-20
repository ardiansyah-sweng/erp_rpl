<?php

namespace App\Http\Controllers;

use App\Models\Merk;
use Illuminate\Http\Request;

class MerkController extends Controller
{
    public function getMerkById($id)
    {
        $merk = (new Merk())->getMerkByID($id);

        if (!$merk) {
            return abort(404, 'Merk tidak ditemukan');
        }

        return view('merk.detail', compact('merk'));
    }
    public function index()
    {
        // memanggil fungsi getMerkAll
        return $this->getMerkAll();
    }

    public function getMerkAll()
    {
        $dataMerk = MerkModel::getMerkAll();

        return response()->json([
            'status' => 'success',
            'data' => $dataMerk
        ]);
    }
}