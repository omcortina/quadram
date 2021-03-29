<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AlmacenController;
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
    return view('login.login');
});
Route::any('usuario/validar', [UsuarioController::class, 'Validar'])->name('usuario/validar');
Route::get('usuario/cerrar_sesion', [UsuarioController::class, 'CerrarSesion'])->name('usuario/cerrar_sesion');
Route::any('usuario/formulario', [UsuarioController::class, 'Formulario'])->name('usuario/formulario');
Route::get('usuario/listado', [UsuarioController::class, 'Listado'])->name('usuario/listado');
Route::any('usuario/perfil/{id_usuario}', [UsuarioController::class, 'Perfil'])->name('usuario/perfil');
Route::any('usuario/cambiar_password', [UsuarioController::class, 'CambiarPassword'])->name('usuario/cambiar_password');
Route::any('usuario/actualizar_perfil', [UsuarioController::class, 'ActualizarPerfil'])->name('usuario/actualizar_perfil');

Route::get('producto/listado', [ProductoController::class, 'Listado'])->name('producto/listado');
Route::get('producto/cargar_archivo', function () {
    return view('producto.cargar_archivo');
})->name('producto/cargar_archivo');

Route::post('producto/importar_excel', [ProductoController::class, 'ImportarExcel'])->name('producto/importar_excel');
Route::any('almacen/informacion', [AlmacenController::class, 'Informacion'])->name('almacen/informacion');

