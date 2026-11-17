

CREATE TABLE `productos` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `nombre` varchar(60) NOT NULL,
    `ubicacion` varchar(20) NOT NULL DEFAULT '',
    `cantidad` int(11) NOT NULL,
    `fecharegistro` timestamp NOT NULL DEFAULT current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;
