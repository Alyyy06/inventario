<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar el formulario de recuperación de contraseña aquí
    // Puedes enviar un correo electrónico al usuario con instrucciones para restablecer su contraseña
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: #333;
    color: #fff;
    margin: 0;
    padding: 0;
}

.container {
    width: 50%;
    margin: 100px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
}

form {
    margin-top: 20px;
    text-align: center;
}

input[type="text"],
input[type="submit"] {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    box-sizing: border-box;
    border-radius: 5px;
    border: 1px solid #ccc;
}

input[type="submit"] {
    background-color: #333;
    color: #fff;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #f8ca00;
    color: #333;
}
    </style>
</head>
<body>
    <div class="container">
        <h1>Recuperar contraseña</h1>
        <form method="POST">
            <input type="text" name="email" placeholder="Correo electrónico" required>
            <input type="submit" value="Enviar instrucciones">
        </form>
    </div>
</body>
</html>