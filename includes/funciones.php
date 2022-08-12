<?php

define('FUNCIONES_URL', __DIR__ . 'funciones.php');
define('TEMPLATES_URL', __DIR__ . '/templates');

function incluirTemplate( string $nombre, bool $inicio = false ) 
{
    include TEMPLATES_URL . "/${nombre}.php";
}

function estaAtenticado() {
    session_start();

    if (!$_SESSION['login']) {
        header('Location: /');
    }
}

function debugear($variable) {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}