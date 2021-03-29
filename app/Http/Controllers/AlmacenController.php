<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlmacenController extends Controller
{
    public function Informacion(){
        return view('almacen.informacion');
    }
}
