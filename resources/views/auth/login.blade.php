<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - SOSMAC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Fondo con Imagen Real y Filtro Estético */
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Aquí está la magia: Un gradiente azul oscuro semitransparente SOBRE tu imagen */
            background: linear-gradient(rgba(11, 21, 54, 0.85), rgba(23, 29, 39, 0.5)), 
                        url("{{ asset('img/bg-login.jpg') }}") no-repeat center center;
            background-size: cover;
            background-attachment: fixed;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-y: auto;
        }

        /* Tarjeta Principal con efecto Cristal (Glassmorphism) y 3D Hover */
        .glass-card {
            background: rgba(255, 255, 255, 0.85); /* Ligeramente más transparente */
            backdrop-filter: blur(12px); /* Desenfoque de cristal real sobre la foto */
            -webkit-backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.6), inset 0 0 0 1px rgba(255, 255, 255, 0.3);
            overflow: hidden;
            width: 900px;
            max-width: 95%;
            display: flex;
            flex-wrap: wrap;
            transform: perspective(1000px) rotateX(0deg) rotateY(0deg);
            transition: transform 0.5s ease, box-shadow 0.5s ease;
        }

        .glass-card:hover {
            transform: perspective(1000px) rotateX(1deg) rotateY(1deg) translateY(-5px);
            box-shadow: 0 35px 60px rgba(0, 0, 0, 0.7);
        }

        /* Panel Izquierdo (Branding semitransparente) */
        .left-panel {
            background: rgba(17, 35, 90, 0.85); /* Azul institucional con 85% de opacidad */
            color: white;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            flex: 1;
            min-width: 300px;
            position: relative;
            overflow: hidden;
        }

        /* Orbes flotantes */
        .left-panel::before, .left-panel::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(45deg, #1E5DDB, #5bc0de);
            opacity: 0.4;
            filter: blur(25px);
            animation: floatOrb 8s ease-in-out infinite;
        }

        .left-panel::before {
            width: 200px; height: 200px; top: -50px; left: -50px;
        }

        .left-panel::after {
            width: 150px; height: 150px; bottom: -20px; right: -30px;
            animation-delay: -4s;
        }

        @keyframes floatOrb {
            0% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-30px) scale(1.1); }
            100% { transform: translateY(0px) scale(1); }
        }

        /* Logo Levitando */
        .logo-container img {
            width: 180px;
            background: transparent;
            padding: 0;
            filter: drop-shadow(0 15px 25px rgba(0,0,0,0.5));
            margin-bottom: 25px;
            z-index: 2;
            position: relative;
            animation: floatLogo 4s ease-in-out infinite;
        }

        @keyframes floatLogo {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        /* Panel Derecho (Formulario) */
        .right-panel {
            padding: 50px 60px;
            flex: 1;
            min-width: 300px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: rgba(255, 255, 255, 0.95);
        }

        /* Inputs Neumórficos */
        .form-control {
            background: #f4f7f6;
            border: 2px solid transparent;
            border-radius: 12px;
            padding: 14px 18px;
            transition: all 0.3s ease;
            box-shadow: inset 0 3px 6px rgba(0,0,0,0.03);
            font-weight: 500;
        }

        .form-control:focus {
            background: #fff;
            border-color: #1E5DDB;
            box-shadow: 0 0 0 4px rgba(30, 93, 219, 0.1), inset 0 2px 5px rgba(0,0,0,0.02);
        }

        /* Botón 3D */
        .btn-login {
            background: linear-gradient(45deg, #11235A, #1E5DDB);
            color: white;
            font-weight: 800;
            letter-spacing: 1px;
            border-radius: 12px;
            padding: 14px;
            border: none;
            box-shadow: 0 10px 20px rgba(30, 93, 219, 0.3);
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(30, 93, 219, 0.4);
            color: white;
        }

        .btn-login:active {
            transform: translateY(1px);
            box-shadow: 0 5px 10px rgba(30, 93, 219, 0.3);
        }

        /* Media Queries para Tablet y Móvil */
        @media (max-width: 768px) {
            body {
                padding: 20px 10px;
                align-items: flex-start; /* Permite mejor scroll en pantallas pequeñas */
            }

            .glass-card {
                flex-direction: column;
                margin-top: 10px;
                margin-bottom: 10px;
                transform: none !important; /* Desactiva 3D hover en móvil para evitar tirones */
            }

            .glass-card:hover {
                transform: none !important;
                box-shadow: 0 25px 50px rgba(0, 0, 0, 0.6);
            }

            .left-panel {
                padding: 40px 20px;
                min-width: 100%;
            }

            .logo-container img {
                width: 130px;
                margin-bottom: 15px;
            }

            .right-panel {
                padding: 40px 30px;
                min-width: 100%;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px 5px;
            }

            .left-panel {
                padding: 30px 15px;
            }

            .logo-container img {
                width: 110px;
            }

            .right-panel {
                padding: 30px 20px;
            }

            .form-control {
                padding: 12px 15px;
            }

            .btn-login {
                padding: 12px;
            }
        }
    </style>
</head>
<body>

    <div class="glass-card">
        <div class="left-panel">
            <div class="logo-container">
                <img src="{{ asset('img/logo.png') }}" alt="SOSMAC">
            </div>
            <h3 class="fw-bold mb-2" style="z-index: 2; position: relative;">Acceso SOSMAC</h3>
            <p style="z-index: 2; position: relative; color: #a8b2d1;">Gestión Ambiental Inteligente</p>
        </div>

        <div class="right-panel">
            <h4 class="fw-bold text-dark mb-4">Ingreso al Sistema</h4>
            
            @if($errors->any())
                <div class="alert alert-danger p-2 small">
                    {{ $errors->first() }}
                </div>
            @endif
            
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px;">CORREO ELECTRÓNICO</label>
                    <input type="email" class="form-control" name="email" placeholder="" required autofocus>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted small" style="letter-spacing: 0.5px;">CONTRASEÑA</label>
                    <input type="password" class="form-control" name="password" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-login w-100 mt-2">INGRESAR AL SISTEMA</button>
            </form>
            
           
        </div>
    </div>

</body>
</html>