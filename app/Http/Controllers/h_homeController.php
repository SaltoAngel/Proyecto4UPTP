<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class h_homeController extends Controller //Tienes el mimso nombres para las clases HomeController y h_homeController
{
    public function index()
    {
        return view('Homepage.index'); //entonces ajustas la ruta aqui, Homepage con H mayuscula indicando la carpeta, el punto lo separa y el index es el archivo blade.php 
    }
}