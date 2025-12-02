<?php
// Script para generar el hash correcto
$password = 'demo123456';
$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
echo "Contraseña: " . $password . "\n";
echo "Hash: " . $hash . "\n";
?>
