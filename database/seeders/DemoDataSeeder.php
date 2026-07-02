<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\Servicio;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\OrdenServicio;
use App\Models\User;
use Illuminate\Support\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear Clientes
        if (Cliente::count() === 0) {
            Cliente::create([
                'tipo_documento' => 'RUC',
                'documento' => '20123456789',
                'nombre_razon_social' => 'Restaurante El Buen Sabor S.A.C.',
                'tipo_cliente' => 'Empresa',
                'telefono' => '987654321',
                'email' => 'contacto@buensabor.pe',
                'direccion' => 'Av. Larco 456, Miraflores, Lima',
                'estado' => true,
            ]);

            Cliente::create([
                'tipo_documento' => 'RUC',
                'documento' => '20987654321',
                'nombre_razon_social' => 'Supermercados Metro S.A.',
                'tipo_cliente' => 'Empresa',
                'telefono' => '912345678',
                'email' => 'operaciones@metro.com.pe',
                'direccion' => 'Av. Alfredo Mendiola 1400, Los Olivos, Lima',
                'estado' => true,
            ]);

            Cliente::create([
                'tipo_documento' => 'DNI',
                'documento' => '45678912',
                'nombre_razon_social' => 'Juan Pérez Gómez',
                'tipo_cliente' => 'Persona Natural',
                'telefono' => '934567890',
                'email' => 'juan.perez@gmail.com',
                'direccion' => 'Calle Las Orquídeas 789, San Isidro, Lima',
                'estado' => true,
            ]);

            Cliente::create([
                'tipo_documento' => 'DNI',
                'documento' => '76543210',
                'nombre_razon_social' => 'María López Rivera',
                'tipo_cliente' => 'Persona Natural',
                'telefono' => '945678901',
                'email' => 'maria.lopez@yahoo.com',
                'direccion' => 'Jr. Huallaga 321, Cercado de Lima',
                'estado' => true,
            ]);
        }

        // 2. Obtener o crear los Servicios base (requeridos para las cotizaciones)
        $servicioDesinsectacion = Servicio::firstOrCreate(
            ['nombre' => 'Desinsectación Integral'],
            [
                'descripcion' => 'Tratamiento contra insectos rastreros.',
                'precio_base' => 32.00,
                'estado' => true
            ]
        );

        $servicioDesinfeccion = Servicio::firstOrCreate(
            ['nombre' => 'Desinfección de Áreas'],
            [
                'descripcion' => 'Desinfección de virus y bacterias por m2.',
                'precio_base' => 40.00,
                'estado' => true
            ]
        );

        $servicioDesratizacion = Servicio::firstOrCreate(
            ['nombre' => 'Desratización Profesional'],
            [
                'descripcion' => 'Control de roedores con cebos certificados.',
                'precio_base' => 65.00,
                'estado' => true
            ]
        );

        $servicioLimpieza = Servicio::firstOrCreate(
            ['nombre' => 'Limpieza de Tanques'],
            [
                'descripcion' => 'Limpieza y lavado de reservorios de agua.',
                'precio_base' => 120.00,
                'estado' => true
            ]
        );

        // 3. Crear Cotizaciones
        if (Cotizacion::count() === 0) {
            $cliente1 = Cliente::where('documento', '20123456789')->first();
            $cliente2 = Cliente::where('documento', '20987654321')->first();
            $cliente3 = Cliente::where('documento', '45678912')->first();

            if ($cliente1) {
                // Cotizacion 1: Restaurante (Aprobada)
                $sub1 = 150.00; // Desinsectación Integral
                $sub2 = 120.00; // Limpieza de Tanques
                $subtotal = $sub1 + $sub2;
                $igv = $subtotal * 0.18;
                $total = $subtotal + $igv;

                $cot1 = Cotizacion::create([
                    'cliente_id' => $cliente1->id,
                    'titulo_proyecto' => 'Fumigación y Limpieza de Reservorios - Local Larco',
                    'direccion_proyecto' => 'Av. Larco 456, Miraflores',
                    'notas_areas' => 'Cocina, comedor, almacén y cisterna de agua.',
                    'notas_materiales' => 'Uso de Deltametrina, gel Maxforce y desinfectantes clorados.',
                    'subtotal' => $subtotal,
                    'igv' => $igv,
                    'total' => $total,
                    'estado' => 'Aprobada',
                    'estado_documento' => 'Emitido',
                ]);

                CotizacionDetalle::create([
                    'cotizacion_id' => $cot1->id,
                    'servicio_id' => $servicioDesinsectacion->id,
                    'cantidad' => 1,
                    'precio_unitario' => 150.00,
                    'subtotal' => $sub1,
                ]);

                CotizacionDetalle::create([
                    'cotizacion_id' => $cot1->id,
                    'servicio_id' => $servicioLimpieza->id,
                    'cantidad' => 1,
                    'precio_unitario' => 120.00,
                    'subtotal' => $sub2,
                ]);

                // Crear Orden de Servicio para esta cotización aprobada
                $tecnico = User::where('email', 'tecnico@sosmac.com')->first();
                OrdenServicio::create([
                    'cotizacion_id' => $cot1->id,
                    'tecnico_id' => $tecnico ? $tecnico->id : null,
                    'fecha_programada' => Carbon::now()->addDays(1)->format('Y-m-d'),
                    'hora_programada' => '09:30:00',
                    'estado' => 'Pendiente',
                    'observaciones_admin' => 'El local abre a las 10:00 AM, el técnico debe estar listo a las 9:15 AM en puerta.',
                    'trabajos_autorizados_por' => 'Carlos Buen Sabor',
                    'pedido_recibido_por' => 'Administrador Principal',
                    'trabajo_descripcion' => 'Fumigación mensual de cocina y limpieza desinfección de tanque de agua.',
                ]);
            }

            if ($cliente2) {
                // Cotizacion 2: Supermercado Metro (Aprobada)
                $sub1 = 180.00 * 2; // Desratización Profesional x 2
                $sub2 = 150.00 * 1; // Desinsectación Integral x 1
                $subtotal = $sub1 + $sub2;
                $igv = $subtotal * 0.18;
                $total = $subtotal + $igv;

                $cot2 = Cotizacion::create([
                    'cliente_id' => $cliente2->id,
                    'titulo_proyecto' => 'Control de Plagas y Desratización - Tienda Los Olivos',
                    'direccion_proyecto' => 'Av. Alfredo Mendiola 1400, Los Olivos',
                    'notas_areas' => 'Almacén de abarrotes, trastienda, zona de descarga.',
                    'notas_materiales' => 'Uso de estaciones cebaderas plásticas con raticida y trampas pegajosas.',
                    'subtotal' => $subtotal,
                    'igv' => $igv,
                    'total' => $total,
                    'estado' => 'Aprobada',
                    'estado_documento' => 'Emitido',
                ]);

                CotizacionDetalle::create([
                    'cotizacion_id' => $cot2->id,
                    'servicio_id' => $servicioDesratizacion->id,
                    'cantidad' => 2,
                    'precio_unitario' => 180.00,
                    'subtotal' => $sub1,
                ]);

                CotizacionDetalle::create([
                    'cotizacion_id' => $cot2->id,
                    'servicio_id' => $servicioDesinsectacion->id,
                    'cantidad' => 1,
                    'precio_unitario' => 150.00,
                    'subtotal' => $sub2,
                ]);

                // Crear Orden de Servicio (En Ruta)
                $tecnico = User::where('email', 'tecnico@sosmac.com')->first();
                OrdenServicio::create([
                    'cotizacion_id' => $cot2->id,
                    'tecnico_id' => $tecnico ? $tecnico->id : null,
                    'fecha_programada' => Carbon::now()->format('Y-m-d'),
                    'hora_programada' => '14:00:00',
                    'estado' => 'En Ruta',
                    'observaciones_admin' => 'Coordinar ingreso con el encargado de almacén. Llevar EPP completo.',
                    'trabajos_autorizados_por' => 'Jefe de Tienda Metro',
                    'pedido_recibido_por' => 'Administrador Principal',
                    'trabajo_descripcion' => 'Desinsectación general y reforzamiento de cebaderos contra roedores.',
                ]);
            }

            if ($cliente3) {
                // Cotizacion 3: Juan Pérez (Pendiente)
                $subtotal = 120.00; // Desinfección de Áreas
                $igv = $subtotal * 0.18;
                $total = $subtotal + $igv;

                $cot3 = Cotizacion::create([
                    'cliente_id' => $cliente3->id,
                    'titulo_proyecto' => 'Desinfección Preventiva de Residencia',
                    'direccion_proyecto' => 'Calle Las Orquídeas 789, San Isidro',
                    'notas_areas' => 'Toda la casa incluyendo cochera y patio trasero.',
                    'notas_materiales' => 'Desinfectante amonio cuaternario quinta generación.',
                    'subtotal' => $subtotal,
                    'igv' => $igv,
                    'total' => $total,
                    'estado' => 'Pendiente',
                    'estado_documento' => 'Borrador',
                ]);

                CotizacionDetalle::create([
                    'cotizacion_id' => $cot3->id,
                    'servicio_id' => $servicioDesinfeccion->id,
                    'cantidad' => 1,
                    'precio_unitario' => 120.00,
                    'subtotal' => $subtotal,
                ]);
            }
        }
    }
}
