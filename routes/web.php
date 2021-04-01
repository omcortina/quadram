<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\LocacionController;
use App\Http\Controllers\EstanteController;

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
Route::get('almacen/ver_listado', [AlmacenController::class, 'VerListado'])->name('almacen/ver_listado');
Route::get('almacen/listado', [AlmacenController::class, 'Listado'])->name('almacen/listado');
Route::any('almacen/nuevo_almacen', [AlmacenController::class, 'Guardar'])->name('almacen/nuevo_almacen');
Route::any('almacen/informacion/{id_almacen}', [AlmacenController::class, 'Informacion'])->name('almacen/informacion');

Route::get('locacion/listado/{id_almacen}', [LocacionController::class, 'Listado'])->name('locacion/listado');
Route::any('locacion/guardar', [LocacionController::class, 'Guardar'])->name('locacion/guardar');
Route::get('locacion/estantes_por_locacion/{id_locacion}', [LocacionController::class, 'EstantesPorLocacion'])->name('locacion/estantes_por_locacion');


Route::get('estante/listado', [EstanteController::class, 'Listado'])->name('estante/listado');
Route::any('estante/guardar', [EstanteController::class, 'Guardar'])->name('estante/guardar');


