<?php

namespace App\Http\Controllers;

use App\Models\AssortmentProduction;

class AssortmentProductionController extends Controller
{
    public function show($id)
    {
        $production = AssortmentProduction::getProductionDetail($id);

        if (!$production) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json($production);
    }

    public function getProductionDetail($production_number)
    {
        return AssortmentProduction::getProductionDetail($production_number);
    }
}
