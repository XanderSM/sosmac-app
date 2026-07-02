<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\OrdenServicio;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Database\Seeder;
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

        // 2. Crear Productos
        if (Producto::count() === 0) {
            Producto::create([
                'nombre' => 'Deltametrina 2.5% EC',
                'tipo' => 'Químico',
                'unidad_medida' => 'Litros',
                'stock' => 50,
                'stock_minimo' => 10,
                'estado' => true,
            ]);

            Producto::create([
                'nombre' => 'Gel Cucarachicida Maxforce',
                'tipo' => 'Químico',
                'unidad_medida' => 'Tubos 30g',
                'stock' => 100,
                'stock_minimo' => 20,
                'estado' => true,
            ]);

            Producto::create([
                'nombre' => 'Trampa Pegajosa para Roedores',
                'tipo' => 'Trampa',
                'unidad_medida' => 'Unidades',
                'stock' => 300,
                'stock_minimo' => 50,
                'estado' => true,
            ]);

            Producto::create([
                'nombre' => 'Estación Cebadera Plástica',
                'tipo' => 'Trampa',
                'unidad_medida' => 'Unidades',
                'stock' => 150,
                'stock_minimo' => 15,
                'estado' => true,
            ]);

            Producto::create([
                'nombre' => 'Raticida Klerat Bloques',
                'tipo' => 'Químico',
                'unidad_medida' => 'Kilos',
                'stock' => 40,
                'stock_minimo' => 8,
                'estado' => true,
            ]);
        }

        // 3. Crear Servicios
        if (Servicio::count() === 0) {
            Servicio::create([
                'nombre' => 'Servicio de Desinsectación General',
                'descripcion' => 'Control de insectos rastreros y voladores (cucarachas, hormigas, moscas, etc.) en ambientes internos y externos.',
                'precio_base' => 150.00,
                'estado' => true,
            ]);

            Servicio::create([
                'nombre' => 'Servicio de Desratización (Control de Roedores)',
                'descripcion' => 'Inspección, colocación de estaciones cebaderas seguras y trampas pegajosas para control de ratas y ratones.',
                'precio_base' => 180.00,
                'estado' => true,
            ]);

            Servicio::create([
                'nombre' => 'Desinfección Ambiental contra Patógenos',
                'descripcion' => 'Nebulización en frío de amonio cuaternario para eliminación de virus, bacterias y hongos en superficies y ambiente.',
                'precio_base' => 120.00,
                'estado' => true,
            ]);

            Servicio::create([
                'nombre' => 'Control Integrado de Plagas en Restaurantes',
                'descripcion' => 'Servicio mensual preventivo y correctivo diseñado especialmente para negocios de comida bajo lineamientos sanitarios (DIGESA).',
                'precio_base' => 250.00,
                'estado' => true,
            ]);
        }

        // 4. Crear Cotizaciones
        if (Cotizacion::count() === 0) {
            $cliente1 = Cliente::where('documento', '20123456789')->first();
            $cliente2 = Cliente::where('documento', '20987654321')->first();
            $cliente3 = Cliente::where('documento', '45678912')->first();

            $servicioDesinsectacion = Servicio::where('nombre', 'like', '%Desinsectación%')->first();
            $servicioDesratizacion = Servicio::where('nombre', 'like', '%Desratización%')->first();
            $servicioRestaurante = Servicio::where('nombre', 'like', '%Restaurantes%')->first();

            if ($cliente1 && $servicioRestaurante) {
                // Cotizacion 1: Restaurante (Aprobada)
                $subtotal = 250.00;
                $igv = $subtotal * 0.18;
                $total = $subtotal + $igv;

                $cot1 = Cotizacion::create([
                    'cliente_id' => $cliente1->id,
                    'titulo_proyecto' => 'Fumigación y Control Integral de Plagas - Local Larco',
                    'direccion_proyecto' => 'Av. Larco 456, Miraflores',
                    'notas_areas' => 'Cocina, comedor, almacén y servicios higiénicos.',
                    'notas_materiales' => 'Uso de Deltametrina y gel Maxforce.',
                    'subtotal' => $subtotal,
                    'igv' => $igv,
                    'total' => $total,
                    'estado' => 'Aprobada',
                    'estado_documento' => 'Emitido',
                ]);

                CotizacionDetalle::create([
                    'cotizacion_id' => $cot1->id,
                    'servicio_id' => $servicioRestaurante->id,
                    'cantidad' => 1,
                    'precio_unitario' => 250.00,
                    'subtotal' => 250.00,
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
                    'trabajo_descripcion' => 'Fumigación mensual de cocina y zonas comunes del restaurante.',
                ]);
            }

            if ($cliente2 && $servicioDesratizacion && $servicioDesinsectacion) {
                // Cotizacion 2: Supermercado Metro (Aprobada)
                $sub1 = 180.00 * 2; // Desratizacion x 2
                $sub2 = 150.00 * 1; // Desinsectacion x 1
                $subtotal = $sub1 + $sub2;
                $igv = $subtotal * 0.18;
                $total = $subtotal + $igv;

                $cot2 = Cotizacion::create([
                    'cliente_id' => $cliente2->id,
                    'titulo_proyecto' => 'Control de Plagas y Desratización - Tienda Los Olivos',
                    'direccion_proyecto' => 'Av. Alfredo Mendiola 1400, Los Olivos',
                    'notas_areas' => 'Almacén de abarrotes, trastienda, zona de descarga.',
                    'notas_materiales' => 'Uso de estaciones cebaderas plásticas con raticida Klerat y trampas pegajosas.',
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

            if ($cliente3 && $servicioDesinsectacion) {
                // Cotizacion 3: Juan Pérez (Pendiente)
                $subtotal = 150.00;
                $igv = $subtotal * 0.18;
                $total = $subtotal + $igv;

                $cot3 = Cotizacion::create([
                    'cliente_id' => $cliente3->id,
                    'titulo_proyecto' => 'Desinsectación de Residencia Particular',
                    'direccion_proyecto' => 'Calle Las Orquídeas 789, San Isidro',
                    'notas_areas' => 'Sala, cocina, jardín interno y habitaciones.',
                    'notas_materiales' => 'Insecticidas de baja toxicidad y sin olor.',
                    'subtotal' => $subtotal,
                    'igv' => $igv,
                    'total' => $total,
                    'estado' => 'Pendiente',
                    'estado_documento' => 'Borrador',
                ]);

                CotizacionDetalle::create([
                    'cotizacion_id' => $cot3->id,
                    'servicio_id' => $servicioDesinsectacion->id,
                    'cantidad' => 1,
                    'precio_unitario' => 150.00,
                    'subtotal' => 150.00,
                ]);
            }
        }
    }
}
