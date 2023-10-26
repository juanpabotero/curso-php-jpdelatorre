<?php

require 'app.php';

function incluirTemplate(string $nombre, bool $inicio = false)
{
    include TEMPLATES_URL . "/$nombre.php";
}

function estaAutenticado(): bool
{
    // Iniciar sesión
    session_start();

    // $_SESSION['login'] es una variable de sesión que se crea en login.php
    return $_SESSION['login'];
}
