<?php
session_start();

$usersFile = 'users.json';
$users = file_exists($usersFile) ? json_decode(file_get_contents($usersFile), true) : [];
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Validar entrada del usuario
    if (empty($username) || empty($password)) {
        $errorMessage = 'Por favor, complete todos los campos.';
    } elseif (strlen($username) < 5 || strlen($password) < 8) {
        $errorMessage = 'Usuario o contraseña no válidos.';
    } else {
        foreach ($users as $user) {
            if ($user['username'] === $username && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['position'] = $user['position'];
                header('Location: index.php');
                exit;
            }
        }
        $errorMessage = 'Usuario o contraseña incorrectos';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Subica</title>
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
        .login-container {
            background-color: #444;
            padding: 20px;
            border-radius: 10px;
            width: 300px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        .login-container h1 {
            color: #f8ca00;
            text-align: center;
        }
        .login-container form {
            display: flex;
            flex-direction: column;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .login-container input[type="submit"] {
            padding: 10px;
            background-color: #f8ca00;
            color: #333;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .login-container input[type="submit"]:hover {
            background-color: #333;
            color: #f8ca00;
        }
        .error-message {
            color: red;
            text-align: center;
        }
        .login-container a {
            color: #f8ca00;
            text-decoration: none;
            text-align: center;
            margin-top: 10px;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
    </style>
        <script>
        function validateForm() {
            const username = document.forms["loginForm"]["username"].value;
            const password = document.forms["loginForm"]["password"].value;
            if (username.length < 5) {
                alert("El nombre de usuario debe tener al menos 5 caracteres.");
                return false;
            }
            if (password.length < 8) {
                alert("La contraseña debe tener al menos 8 caracteres.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="login-container">
        <h1>Iniciar Sesión</h1>
        <?php if ($errorMessage): ?>
            <p class="error-message"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <form name="loginForm" action="login.php" method="POST" onsubmit="return validateForm()">
            <input type="text" name="username" placeholder="Nombre de usuario" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <input type="submit" value="Iniciar Sesión">
        </form>
        <a href="register.php">Crear cuenta</a>
    </div>
</body>
</html>


