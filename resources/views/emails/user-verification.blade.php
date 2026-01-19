<!DOCTYPE html>
<html>
<head>
    <title>Código de Verificación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
            background-color: #f9f9f9;
        }
        .code {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background-color: #fff;
            border: 2px dashed #007bff;
            border-radius: 5px;
            letter-spacing: 2px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Código de Verificación</h2>
        <p>Se está intentando crear un usuario en el sistema con su correo electrónico.</p>
        <p>Su código de verificación es:</p>
        
        <div class="code">{{ $code }}</div>
        
        <p>Este código es válido por 30 minutos.</p>
        <p>Comparta este código con el administrador para completar el proceso de creación de usuario.</p>
        
        <div class="footer">
            <p><small>Si no solicitó la creación de un usuario, por favor ignore este mensaje.</small></p>
        </div>
    </div>
</body>
</html>