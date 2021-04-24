<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\ConteoController;
use App\Http\Controllers\LocacionController;
use App\Http\Controllers\EstanteController;
use App\Http\Controllers\SeguimientoAuditoriaController;
use App\Http\Controllers\SeguimientoConteoController;
use App\Http\Controllers\APIController;


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
//USUARIO
Route::any('usuario/validar', [UsuarioController::class, 'Validar'])->name('usuario/validar');
Route::get('usuario/cerrar_sesion', [UsuarioController::class, 'CerrarSesion'])->name('usuario/cerrar_sesion');
Route::any('usuario/formulario', [UsuarioController::class, 'Formulario'])->name('usuario/formulario');
Route::get('usuario/listado', [UsuarioController::class, 'Listado'])->name('usuario/listado');
Route::any('usuario/perfil/{id_usuario}', [UsuarioController::class, 'Perfil'])->name('usuario/perfil');
Route::any('usuario/cambiar_password', [UsuarioController::class, 'CambiarPassword'])->name('usuario/cambiar_password');
Route::any('usuario/actualizar_perfil', [UsuarioController::class, 'ActualizarPerfil'])->name('usuario/actualizar_perfil');

//PRODUCTO
Route::get('producto/listado', [ProductoController::class, 'Listado'])->name('producto/listado');
Route::get('producto/cargar_archivo', function () { return view('producto.cargar_archivo');})->name('producto/cargar_archivo');
Route::post('producto/importar_excel', [ProductoController::class, 'ImportarExcel'])->name('producto/importar_excel');

//ALMACEN
Route::get('almacen/ver_listado', [AlmacenController::class, 'VerListado'])->name('almacen/ver_listado');
Route::get('almacen/listado', [AlmacenController::class, 'Listado'])->name('almacen/listado');
Route::any('almacen/nuevo_almacen', [AlmacenController::class, 'Guardar'])->name('almacen/nuevo_almacen');
Route::any('almacen/informacion/{id_almacen}', [AlmacenController::class, 'Informacion'])->name('almacen/informacion');

//LOCACION
Route::get('locacion/listado/{id_almacen}', [LocacionController::class, 'Listado'])->name('locacion/listado');
Route::any('locacion/guardar', [LocacionController::class, 'Guardar'])->name('locacion/guardar');
Route::get('locacion/estantes_por_locacion/{id_locacion}', [LocacionController::class, 'EstantesPorLocacion'])->name('locacion/estantes_por_locacion');

//ESTANTE
Route::get('estante/listado', [EstanteController::class, 'Listado'])->name('estante/listado');
Route::any('estante/guardar', [EstanteController::class, 'Guardar'])->name('estante/guardar');

//FILA
Route::get('fila/listado', [EstanteController::class, 'ListadoFilas'])->name('fila/listado');
Route::any('fila/guardar', [EstanteController::class, 'GuardarFila'])->name('fila/guardar');

//INVENTARIO
Route::get('inventario/gestion', [InventarioController::class, 'Gestion'])->name('inventario/gestion');
Route::get('inventario/obtener_listado', [InventarioController::class, 'ObtenerListado'])->name('inventario/obtener_listado');
Route::post('inventario/guardar', [InventarioController::class, 'Guardar'])->name('inventario/guardar');

//AUDITORIA
Route::get('auditoria/listado/{id_inventario}', [AuditoriaController::class, 'Listado'])->name('auditoria/listado');
Route::any('auditoria/gestion', [AuditoriaController::class, 'Gestion'])->name('auditoria/gestion');
Route::get('auditoria/buscar_locaciones/{id_almacen}', [AuditoriaController::class, 'BuscarLocaciones'])->name('auditoria/buscar_locaciones');
Route::any('auditoria/guardar', [AuditoriaController::class, 'Guardar'])->name('auditoria/guardar');
Route::any('auditoria/informe/{id_auditoria}', [AuditoriaController::class, 'Imprimir'])->name('auditoria/informe');

//SEGUIMIENTO AUDITORIA
Route::any('auditoria/seguimiento', [SeguimientoAuditoriaController::class, 'Informe'])->name('auditoria/seguimiento');
Route::any('conteo/seguimiento', [SeguimientoConteoController::class, 'Informe'])->name('conteo/seguimiento');
Route::any('conteo/informe/{id_conteo}', [ConteoController::class, 'Imprimir'])->name('conteo/informe');


//API
Route::post('api/login', [APIController::class, 'Login'])->name('api/login');
Route::post('api/auditor/audits', [APIController::class, 'Auditorias']);
Route::post('api/auditor/saveTracing', [APIController::class, 'GuardarSeguimientoAuditoria']);
Route::delete('api/auditor/deleteTracing', [APIController::class, 'BorrarSeguimientoAuditoria'])->name('api/auditor/deleteTracing');
Route::any('api/auditor/getLocations', [APIController::class, 'LocacionesAuditoriaAuditor'])->name('api/auditor/getLocations');
Route::post('api/auditor/auditHistory', [APIController::class, 'HistorialAuditorias'])->name('api/auditor/auditHistory');

Route::post('api/getProductByBarcode', [APIController::class, 'BuscarProductoPorCodigoBarra']);

Route::post('api/counter/counts', [APIController::class, 'Conteos']);
Route::post('api/counter/saveTracing', [APIController::class, 'GuardarSeguimientoConteo']);
Route::delete('api/counter/deleteTracing', [APIController::class, 'BorrarSeguimientoConteo'])->name('api/counter/deleteTracing');
Route::any('api/counter/getLocations', [APIController::class, 'LocacionesConteoContador'])->name('api/counter/getLocations');
Route::post('api/counter/countsHistory', [APIController::class, 'HistorialConteos'])->name('api/counter/countsHistory');
Route::post('api/counter/finalizeCountDetail', [APIController::class, 'FinalizarConteo'])->name('api/counter/finalizeCountDetail');

