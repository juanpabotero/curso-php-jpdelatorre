<?php

// habilitar tipado estricto
declare(strict_types=1);

include 'includes/header.php';


function sumar(int $numero1 = 0, float $numero2 = 0)
{
    echo $numero1 + $numero2;
}

sumar(10, 2);



include 'includes/footer.php';
