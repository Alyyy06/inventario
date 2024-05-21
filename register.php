<?php
session_start();
$usersFile = 'users.json';
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];
$errorMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $position = $_POST['position'];

    if ($password !== $confirmPassword) {
        $errorMessage = 'Las contraseñas no coinciden';
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $users[] = [
            'username' => $username,
            'password' => $hashedPassword,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'position' => $position
        ];
        file_put_contents($usersFile, json_encode($users));
        $successMessage = 'Usuario registrado exitosamente';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Subica</title>
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
        .register-container {
            background-color: #444;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        .register-container h1 {
            color: #f8ca00;
            text-align: center;
        }
        .register-container form {
            display: flex;
            flex-direction: column;
        }
        .register-container input[type="text"],
        .register-container input[type="password"],
        .register-container select {
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .register-container input[type="submit"] {
            padding: 10px;
            background-color: #f8ca00;
            color: #333;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .register-container input[type="submit"]:hover {
            background-color: #333;
            color: #f8ca00;
        }
        .error-message, .success-message {
            color: red;
            text-align: center;
        }
        .success-message {
            color: green;
        }
        .register-container a {
            color: #f8ca00;
            text-decoration: none;
            text-align: center;
            margin-top: 10px;
        }
        .register-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1>Registro</h1>
        <?php if ($errorMessage): ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php elseif ($successMessage): ?>
            <p class="success-message"><?php echo $successMessage; ?></p>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <input type="text" name="first_name" placeholder="Nombre" required>
            <input type="text" name="last_name" placeholder="Apellido" required>
            <input type="text" name="username" placeholder="Nombre de usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="password" name="confirm_password" placeholder="Confirmar Contraseña" required>
            <select name="position" required>
                <option value="" disabled selected>Selecciona tu cargo</option>
                <option value="procura">Procura</option>
                <option value="logística">Logística</option>
                <option value="almacén">Almacén</option>
            </select>
            <input type="submit" value="Registrar">
        </form>
        <a href="login.php">Iniciar Sesión</a>
    </div>
</body>
</html>





