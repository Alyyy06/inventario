<?php
session_start();
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php'); // Cambiado a login.php
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subica</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #333;
            color: #fff;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #f8ca00;
            overflow: hidden;
            display: flex; /* Para usar flexbox */
            justify-content: space-between; /* Para separar los elementos */
            align-items: center; /* Para centrar verticalmente */
            padding: 10px 20px; /* Ajusta el padding según sea necesario */
        }
        .navbar img {
            max-width: 100px; /* Ajusta el tamaño del logo */
        }
        .navbar a {
            color: #333;
            text-decoration: none;
            margin: 0 20px;
        }
        .navbar a:hover {
            color: #f8ca00;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
            text-align: center;
            padding-top: 20px;
        }
        h1 {
            color: #f8ca00;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            background-color: #f8ca00;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
            border: 1px solid #333;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #333;
            color: #f8ca00;
        }
        .container img {
            margin-top: 20px;
            max-width: 100%; /* Ajusta el tamaño máximo de la imagen */
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="#"><img src="WhatsApp Image 2024-05-20 at 9.17.48 AM.jpeg" alt="Logo de la empresa"></a>
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <a href="login.php?logout=true">Cerrar Sesión</a> <!-- Modificado a login.php -->
        <?php endif; ?>
    </div>
    <div class="container">
        <h1>Subica</h1>
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <a class="btn" href="materiales.php">Materiales</a>
            <a class="btn" href="herramientas.php">Herramientas</a>
            <a class="btn" href="equipos.php">Equipos</a>
            <img src="ruta_de_la_imagen.jpg" alt="Descripción de la imagen">
        <?php else: ?>
            <p>Por favor, inicie sesión para acceder a los recursos.</p>
        <?php endif; ?>
    </div>
</body>
</html>

