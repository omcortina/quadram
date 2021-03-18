<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductoController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('layouts.principal');
});

Route::any('usuario/formulario', [UsuarioController::class, 'Formulario'])->name('usuario/formulario');
Route::get('usuario/listado', [UsuarioController::class, 'Listado'])->name('usuario/listado');

Route::get('producto/listado', [ProductoController::class, 'Listado'])->name('producto/listado');
Route::get('producto/cargar_archivo', function () {
    return view('producto.cargar_archivo');
})->name('producto/cargar_archivo');

Route::post('producto/importar_excel', [ProductoController::class, 'ImportarExcel'])->name('producto/importar_excel');

