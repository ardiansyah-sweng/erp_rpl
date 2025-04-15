<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function getItemAll(){
        return (new Item)->getItem();
    }

    public function getItemByID($id){
         $item = (new Item)->getItemByID($id);
        // return (new Item)->getItemByID($id);
        return response()->json($item);
        
    }
}
