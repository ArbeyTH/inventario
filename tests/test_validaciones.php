<?php
require_once __DIR__ . '/../src/validaciones.php';

$casos = [
    ["Teclado", true],
    ["", false],
    ["3", true],
    ["Mouse", false]
];

$aprobadas = 0;
$fallidas = 0;

foreach ($casos as [$entrada, $esperado]) {
    $obtenido = validarNombreProducto($entrada);

    if ($obtenido === $esperado) {
        echo "PASS\n";
        $aprobadas++;
    } else {
        echo "FAIL\n";
        $fallidas++;
    }
}

echo "\nTotal de pruebas: " . count($casos) . "\n";
echo "Pruebas aprobadas: " . $aprobadas . "\n";
echo "Pruebas fallidas: " . $fallidas . "\n";