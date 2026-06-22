<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClienteController; 
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\CotizacionDetalleController;
use App\Http\Controllers\OrdenServicioController;
use App\Http\Controllers\TecnicoController;
use App\Http\Controllers\AdminTecnicoController;
use App\Http\Controllers\DashboardController;

// Rutas públicas (Login)
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas protegidas (Requieren autenticación)
Route::middleware('auth')->group(function () {
    
    // ==========================================
    // MUNDO ADMINISTRADOR
    // ==========================================

    // Dashboard Operativo
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/reportes/exportar', [DashboardController::class, 'exportarReporte'])->name('admin.reportes.exportar');

    // Módulo de Clientes
    Route::get('/admin/clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::post('/admin/clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::put('/admin/clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/admin/clientes/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy');

    // Módulo de Inventario
    Route::get('/admin/inventario', [ProductoController::class, 'index'])->name('inventario.index');
    Route::post('/admin/inventario', [ProductoController::class, 'store'])->name('inventario.store');
    Route::put('/admin/inventario/{id}', [ProductoController::class, 'update'])->name('inventario.update');
    Route::delete('/admin/inventario/{id}', [ProductoController::class, 'destroy'])->name('inventario.destroy');

    // Catálogo de Servicios (CRUD Completo)
    Route::get('/admin/servicios', [ServicioController::class, 'index'])->name('servicios.index');
    Route::post('/admin/servicios', [ServicioController::class, 'store'])->name('servicios.store');
    Route::put('/admin/servicios/{id}', [ServicioController::class, 'update'])->name('servicios.update');
    Route::delete('/admin/servicios/{id}', [ServicioController::class, 'destroy'])->name('servicios.destroy');

    // Módulo de Cotizaciones
    Route::get('/admin/cotizaciones', [CotizacionController::class, 'index'])->name('cotizaciones.index');
    Route::get('/admin/cotizaciones/crear', [CotizacionController::class, 'create'])->name('cotizaciones.create');
    Route::post('/admin/cotizaciones', [CotizacionController::class, 'store'])->name('cotizaciones.store');
    Route::put('/admin/cotizaciones/{id}/estado', [CotizacionController::class, 'cambiarEstado'])->name('cotizaciones.estado');
    Route::delete('/admin/cotizaciones/{id}', [CotizacionController::class, 'destroy'])->name('cotizaciones.destroy');
    Route::get('/admin/cotizaciones/{id}/pdf', [CotizacionController::class, 'descargarPdf'])->name('cotizaciones.pdf');

    // Módulo de Órdenes de Servicio
    Route::get('/admin/ordenes', [OrdenServicioController::class, 'index'])->name('ordenes.index');
    Route::post('/admin/ordenes', [OrdenServicioController::class, 'store'])->name('ordenes.store');
    Route::put('/admin/ordenes/{id}', [OrdenServicioController::class, 'update'])->name('ordenes.update');
    Route::delete('/admin/ordenes/{id}', [OrdenServicioController::class, 'destroy'])->name('ordenes.destroy');
    Route::get('/admin/ordenes/{id}/pdf-orden', [OrdenServicioController::class, 'descargarOrdenPdf'])->name('ordenes.pdf_orden');
    Route::get('/admin/ordenes/{id}/pdf-comprobante', [OrdenServicioController::class, 'descargarComprobantePdf'])->name('ordenes.pdf_comprobante');

    // Módulo de Técnicos
    Route::get('/admin/tecnicos', [AdminTecnicoController::class, 'index'])->name('tecnicos.index');
    Route::post('/admin/tecnicos', [AdminTecnicoController::class, 'store'])->name('tecnicos.store');
    Route::delete('/admin/tecnicos/{id}', [AdminTecnicoController::class, 'destroy'])->name('tecnicos.destroy');


    // ==========================================
    // MUNDO TÉCNICO (Portal Móvil)
    // ==========================================
    
    Route::get('/tecnico/portal', [TecnicoController::class, 'index'])->name('tecnico.portal');
    Route::put('/tecnico/ordenes/{id}/estado', [TecnicoController::class, 'actualizarEstado'])->name('tecnico.ordenes.estado');

});