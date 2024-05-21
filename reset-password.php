<?php
session_start();
$usersFile = 'users.json';
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];
$errorMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    $userFound = false;

    if ($newPassword !== $confirmPassword) {
        $errorMessage = 'Las contraseñas no coinciden';
    } else {
        foreach ($users as &$user) {
            if (isset($user['reset_token']) && $user['reset_token'] === $token && $user['reset_token_expiry'] > time()) {
                $userFound = true;
                $user['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
                unset($user['reset_token']);
                unset($user['reset_token_expiry']);
                break;
            }
        }

        if ($userFound) {
            file_put_contents($usersFile, json_encode($users));
            $successMessage = 'Contraseña restablecida con éxito';
        } else {
            $errorMessage = 'Token inválido o expirado';
        }
    }
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    header('Location: forgot-password.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - Subica</title>
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
        .reset-container {
            background-color: #444;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        .reset-container h1 {
            color: #f8ca00;
            text-align: center;
        }
        .reset-container form {
            display: flex;
            flex-direction: column;
        }
        .reset-container input[type="password"] {
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .reset-container input[type="submit"] {
            padding: 10px;
            background-color: #f8ca00;
            color: #333;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .reset-container input[type="submit"]:hover {
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
        .reset-container a {
            color: #f8ca00;
            text-decoration: none;
            text-align: center;
            margin-top: 10px;
        }
        .reset-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h1>Restablecer Contraseña</h1>
        <?php if ($errorMessage): ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <?php if ($successMessage): ?>
            <p class="success-message"><?php echo $successMessage; ?></p>
        <?php endif; ?>
        <form action="reset-password.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <input type="password" name="new_password" placeholder="Nueva Contraseña" required>
            <input type="password" name="confirm_password" placeholder="Confirmar Contraseña" required>
            <input type="submit" value="Restablecer Contraseña">
        </form>
        <a href="login.php">Iniciar Sesión</a>
    </div>
</body>
</html>