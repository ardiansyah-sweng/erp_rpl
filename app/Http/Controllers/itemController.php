<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function getItemAll(){
        return (new Item)->getItem();
    }
}
