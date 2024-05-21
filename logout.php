<?php
session_start();
session_destroy();
session_regenerate_id(true); // Regenerar ID de sesión por seguridad
header('Location: login.php');
exit;
?>
