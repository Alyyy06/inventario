<?php
session_start();
$usersFile = 'users.json';
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];
$errorMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $userFound = false;

    foreach ($users as $user) {
        if ($user['username'] === $username) {
            $userFound = true;
            $resetToken = bin2hex(random_bytes(16));
            $user['reset_token'] = $resetToken;
            $user['reset_token_expiry'] = time() + 3600; // Token válido por 1 hora
            break;
        }
    }

    if ($userFound) {
        file_put_contents($usersFile, json_encode($users));
        // Simulación del envío de correo
        $resetLink = "http://yourdomain.com/reset-password.php?token=$resetToken";
        $successMessage = "Se ha enviado un enlace de restablecimiento a tu correo (simulado): $resetLink";
    } else {
        $errorMessage = 'El nombre de usuario no existe';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Subica</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #333;
            color: #fff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .forgot-container {
            background-color: #444;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        .forgot-container h1 {
            color: #f8ca00;
            text-align: center;
        }
        .forgot-container form {
            display: flex;
            flex-direction: column;
        }
        .forgot-container input[type="text"] {
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .forgot-container input[type="submit"] {
            padding: 10px;
            background-color: #f8ca00;
            color: #333;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .forgot-container input[type="submit"]:hover {
            background-color: #333;
            color: #f8ca00;
        }
        .error-message, .success-message {
            text-align: center;
            margin: 10px 0;
        }
        .error-message {
            color: red;
        }
        .success-message {
            color: green;
        }
        .forgot-container a {
            color: #f8ca00;
            text-decoration: none;
            text-align: center;
            margin-top: 10px;
        }
        .forgot-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <h1>Recuperar Contraseña</h1>
        <?php if ($errorMessage): ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <?php if ($successMessage): ?>
            <p class="success-message"><?php echo $successMessage; ?></p>
        <?php endif; ?>
        <form action="forgot-password.php" method="POST">
            <input type="text" name="username" placeholder="Nombre de usuario" required>
            <input type="submit" value="Enviar Enlace de Recuperación">
        </form>
        <a href="login.php">Iniciar Sesión</a>
    </div>
</body>
</html>