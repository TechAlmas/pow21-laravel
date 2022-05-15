<?php 

echo("OS: " . PHP_OS . " </br>");
if (strtolower(substr(PHP_OS, 0, 3)) !== 'win') {
    $target = __DIR__ . '/storage/app/uploads';
    $link = __DIR__ . '/public/uploads';
    echo("symlink de: $target a: $link </br>");
    $resultado = symlink($target, $link);

    if ($resultado) {
        echo("symlink de uploads creado.<br/><br/>");
    } else {
        echo("symlink de uploads fallido.<br/><br/>");
    }
} else {
    $target = __DIR__ . '\\storage\\app';
    $link = __DIR__ . '\\public\\uploads';
    exec("mklink /J \"{$link}\" \"{$target}\"", $resultado, $codigo);
    echo("{$codigo}<br/><br/>");
}