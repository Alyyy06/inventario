<?php
// Establecer la zona horaria a Venezuela
date_default_timezone_set('America/Caracas');

$file = 'equipos.json'; // Cambiado de 'herramientas.json' a 'equipos.json'
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $data[] = ['nombre' => $_POST['nombre'], 'cantidad_inicial' => $_POST['cantidad'], 'unidad_medida' => $_POST['unidad_medida'], 'retiros' => []];
    } elseif (isset($_POST['modify'])) {
        foreach ($data as &$item) {
            if ($item['nombre'] === $_POST['nombre']) {
                $item['cantidad_inicial'] += $_POST['cantidad'];
                break;
            }
        }
    } elseif (isset($_POST['delete'])) {
        $data = array_filter($data, function($item) {
            return $item['nombre'] !== $_POST['nombre'];
        });
    } elseif (isset($_POST['retirar'])) {
        foreach ($data as &$item) {
            if ($item['nombre'] === $_POST['nombre']) {
                $retiradoPor = $_POST['retirado_por'];
                $cantidadRetirar = $_POST['cantidad'];
                $item['retiros'][] = ['fecha' => date("Y-m-d H:i:s"), 'cantidad' => $cantidadRetirar, 'retirado_por' => $retiradoPor];
                $item['cantidad_restante'] = $item['cantidad_inicial'] - array_sum(array_column($item['retiros'], 'cantidad'));
                break;
            }
        }
    } elseif (isset($_POST['eliminar_retiros'])) {
        foreach ($data as &$item) {
            if ($item['nombre'] === $_POST['nombre']) {
                $item['retiros'] = [];
                $item['cantidad_restante'] = $item['cantidad_inicial'];
                break;
            }
        }
    }
    file_put_contents($file, json_encode($data));
}

$searchTerm = isset($_GET['search']) ? strtolower($_GET['search']) : '';
$filteredData = array_filter($data, function($item) use ($searchTerm) {
    return strpos(strtolower($item['nombre']), $searchTerm) !== false;
});
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipos</title> <!-- Cambiado de 'Herramientas' a 'Equipos' -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #333;
            color: #fff;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        h1 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #fff;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f8ca00;
        }
        tr:nth-child(even) {
            background-color: #666;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"], input[type="number"], select {
            padding: 5px;
            margin: 5px 0;
        }
        input[type="submit"] {
            padding: 5px 10px;
            background-color: #f8ca00;
            color: #333;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #333;
            color: #f8ca00;
        }
        .back-button {
            display: block;
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #f8ca00;
            color: #333;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-button:hover {
            background-color: #333;
            color: #f8ca00;
        }
        .navbar {
            background-color: #f8ca00;
            overflow: hidden;
        }
        .navbar a {
            float: left;
            display: block;
            color: #333;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #333;
            color: #f8ca00;
        }
        h1 {            color: #f8ca00;
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
        .search-container {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
        }
        .search-container input[type="text"] {
            padding: 5px;
            width: 300px;
        }
        .autocomplete-list {
            position: absolute;
            background-color: #f1f1f1;
            z-index: 99;
            list-style-type: none;
            margin: 0;
            padding: 0;
            width: 300px;
            overflow-y: auto;
            border: 1px solid #ddd;
        }
        .autocomplete-item {
            padding: 10px;
            cursor: pointer;
        }
        .autocomplete-item:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
<div class="navbar">
    <a href="index.php">Inicio</a>
    <a href="materiales.php">Materiales</a>
    <a href="herramientas.php">Herramientas</a>
    <a href="equipos.php">Equipos</a>
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <a href="index.php?logout=true" style="float:right">Cerrar Sesión</a>
    <?php else: ?>
        <a href="login.php" style="float:right">Cerrar Sesión</a>
    <?php endif; ?>
</div>

<div class="container">
    <h1>Equipos</h1>

    <div class="search-container">
        <input type="text" id="search" placeholder="Buscar equipos">
        <ul class="autocomplete-list" id="autocomplete-list"></ul>
    </div>

    <form method="POST">
        <h3>Agregar Equipo</h3>
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="number" name="cantidad" placeholder="Cantidad" required>
        <select name="unidad_medida" required>
            <option value="unidad">Unidad</option>
            <option value="pieza">Pieza</option>
            <option value="kilo">Kilo</option>
            <option value="metro">Metro</option>
            <option value="galon">Galón</option>
            <option value="litro">Litro</option>
            <option value="gramo">Gramo</option>
        </select>
        <input type="submit" name="add" value="Agregar">
    </form>
    <form method="POST">
        <h3>Agregar Cantidad de Equipos</h3>
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="number" name="cantidad" placeholder="Nueva Cantidad" required>
        <input type="submit" name="modify" value="Modificar">
    </form>
    <table>
        <tr>
            <th>Nombre</th>
            <th>Cantidad Inicial</th>
            <th>Unidad de Medida</th>
            <th>Retiros</th>
            <th>Cantidad Disponible</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($filteredData as $item): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['nombre']); ?></td>
            <td><?php echo htmlspecialchars($item['cantidad_inicial']); ?></td>
            <td><?php echo htmlspecialchars($item['unidad_medida']); ?></td>
            <td>
                <?php foreach ($item['retiros'] as $retiro): ?>
                    <?php echo "{$retiro['cantidad']} retirado por {$retiro['retirado_por']} el {$retiro['fecha']}<br>"; ?>
                <?php endforeach; ?>
            </td>
            <td><?php echo htmlspecialchars($item['cantidad_restante']); ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($item['nombre']); ?>">
                    <input type="number" name="cantidad" placeholder="Cantidad" required>
                    <input type="text" name="retirado_por" placeholder="Retirado por" required>
                    <input type="submit" name="retirar" value="Retirar">
                </form>
                <form method="POST">
                    <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($item['nombre']); ?>">
                    <input type="submit" name="eliminar_retiros" value="Eliminar Retiros">
                </form>
                <form method="POST">
                    <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($item['nombre']); ?>">
                    <input type="submit" name="delete" value="Eliminar">
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a class="back-button" href="index.php">Volver</a>
</div>
</body>
</html>

<script>
    const equipos = <?php echo json_encode(array_column($data, 'nombre')); ?>;
    const searchInput = document.getElementById('search');
    const autocompleteList = document.getElementById('autocomplete-list');

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase();
        autocompleteList.innerHTML = '';
        if (query.length > 0) {
            const filteredEquipos = equipos.filter(equipo => equipo.toLowerCase().includes(query));
            filteredEquipos.forEach(equipo => {
                const item = document.createElement('li');
                item.classList.add('autocomplete-item');
                item.textContent = equipo;
                item.addEventListener('click', () => {
                    searchInput.value = equipo;
                    autocompleteList.innerHTML = '';
                    window.location.href = `equipos.php?search=${equipo}`;
                });
                autocompleteList.appendChild(item);
            });
            autocompleteList.style.display = 'block';
        } else {
            autocompleteList.style.display = 'none';
        }
    });

    document.addEventListener('click', function(event) {
        if (!autocompleteList.contains(event.target) && event.target !== searchInput) {
            autocompleteList.style.display = 'none';
        }
    });
</script>




