
<?php

require_once __DIR__ . '/../src/validaciones.php';

$conexion = null;
$codigoSalida = 0;

try {

    /** @var PDO $conexion */
    $conexion = require __DIR__ . '/conexion_pruebas.php';

    echo "Conectado: Se conectó a la base inventarioPruebas."
        . PHP_EOL;

    $conexion->beginTransaction();


    // ==========================================
    // PRUEBA I1: REGISTRO CON DATOS VÁLIDOS
    // ==========================================

    echo PHP_EOL . "--- Prueba I1: Datos válidos ---"
        . PHP_EOL;

    $nombre = 'Prueba';
    $ubicacion = 'Bodega';
    $cantidad = 5;

    if (
        validarNombreProducto($nombre)
        && validarUbicacion($ubicacion)
        && validarCantidadProducto($cantidad)
    ) {

        $consulta = $conexion->prepare(
            'INSERT INTO productos (nombre, ubicacion, cantidad)
             VALUES (:nombre, :ubicacion, :cantidad)'
        );

        $consulta->execute([
            'nombre' => $nombre,
            'ubicacion' => $ubicacion,
            'cantidad' => $cantidad
        ]);

        $id = $conexion->lastInsertId();

        echo "Correcto: inserción realizada. ID: $id"
            . PHP_EOL;


        $consulta = $conexion->prepare(
            'SELECT nombre, ubicacion, cantidad
             FROM productos
             WHERE id = :id'
        );

        $consulta->execute(['id' => $id]);

        $producto = $consulta->fetch();


        if (
            $producto !== false
            && $producto['nombre'] === $nombre
            && $producto['ubicacion'] === $ubicacion
            && (int) $producto['cantidad'] === $cantidad
        ) {

            echo "CORRECTO: nombre, ubicación y cantidad coinciden."
                . PHP_EOL;

        } else {

            echo "ERROR: producto no encontrado o datos diferentes."
                . PHP_EOL;

            $codigoSalida = 1;
        }

    } else {

        echo "ERROR: los datos válidos fueron rechazados."
            . PHP_EOL;

        $codigoSalida = 1;
    }


    // ==========================================
    // PRUEBA I2: REGISTRO CON DATOS INVÁLIDOS
    // ==========================================

    echo PHP_EOL . "--- Prueba I2: Datos inválidos ---"
        . PHP_EOL;

    $nombreInvalido = 'Producto';
    $ubicacionInvalida = 'Casa';
    $cantidadInvalida = 5;

    $antes = (int) $conexion->query(
        'SELECT COUNT(*) FROM productos'
    )->fetchColumn();


    if (
        validarNombreProducto($nombreInvalido)
        && validarUbicacion($ubicacionInvalida)
        && validarCantidadProducto($cantidadInvalida)
    ) {

        $consulta = $conexion->prepare(
            'INSERT INTO productos (nombre, ubicacion, cantidad)
             VALUES (:nombre, :ubicacion, :cantidad)'
        );

        $consulta->execute([
            'nombre' => $nombreInvalido,
            'ubicacion' => $ubicacionInvalida,
            'cantidad' => $cantidadInvalida
        ]);
    }


    $despues = (int) $conexion->query(
        'SELECT COUNT(*) FROM productos'
    )->fetchColumn();


    if (
        !validarUbicacion($ubicacionInvalida)
        && $antes === $despues
    ) {

        echo "CORRECTO: ubicación inválida rechazada."
            . PHP_EOL;

        echo "CORRECTO: no se insertó ningún registro."
            . PHP_EOL;

    } else {

        echo "ERROR: no se rechazaron correctamente los datos."
            . PHP_EOL;

        $codigoSalida = 1;
    }


} catch (Throwable $error) {

    echo "ERROR: " . $error->getMessage() . PHP_EOL;

    $codigoSalida = 1;

} finally {

    if ($conexion !== null && $conexion->inTransaction()) {

        $conexion->rollBack();

        echo PHP_EOL . "Limpieza: inserciones de prueba eliminadas."
            . PHP_EOL;
    }
}

exit($codigoSalida);
