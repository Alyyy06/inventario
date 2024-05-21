<?php
session_start();

// Ruta al archivo JSON que contiene los usuarios
$usersFile = 'users.json';

// Verifica si se ha enviado el formulario de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Carga los usuarios almacenados en el archivo JSON
    $users = json_decode(file_get_contents($usersFile), true);

    // Obtiene los datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verifica si el usuario ya existe
    if (isset($users[$username])) {
        $error = 'El usuario ya existe';
    } else {
        // Agrega el nuevo usuario al archivo JSON
        $users[$username] = $password;
        file_put_contents($usersFile, json_encode($users));

        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header('Location: home.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subica - Registrarse</title>
    <style>
        /* Estilos aquí */
    </style>
</head>
<body>
    <div class="container">
        <h1>Registrarse</h1>
        <?php if (isset($error)): ?>
            <p><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="submit" value="Registrarse">
        </form>
        <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión</a></p>
    </div>
</body>
</html>