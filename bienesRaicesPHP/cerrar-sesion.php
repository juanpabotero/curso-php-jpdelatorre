<?php 

// iniciar sesión
session_start();

// borrar los datos de la sesión
$_SESSION = [];

// redireccionar
header('Location: /');