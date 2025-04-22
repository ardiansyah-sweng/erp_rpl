<?php

namespace App\Http\Controllers;

use App\Models\MerkModel;

class MerkController_salman extends Controller
{
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