<?php

// Función simple para cargar variables de entorno
function loadEnv($filePath) {
  if (!file_exists($filePath)) {
      throw new Exception("Archivo .env no encontrado en: $filePath");
  }

  $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
      // Ignorar comentarios
      if (strpos(trim($line), '#') === 0) {
          continue;
      }

      // Dividir por el primer signo =
      list($name, $value) = explode('=', $line, 2);
      $name = trim($name);
      $value = trim($value);

      // Remover comillas si existen
      $value = trim($value, '"\'');

      // Establecer variable de entorno si no existe
      if (!array_key_exists($name, $_ENV)) {
          $_ENV[$name] = $value;
      }
  }
}

// Cargar variables de entorno
$envPath = __DIR__ . '/../.env';
loadEnv($envPath);

// Función helper para obtener variables de entorno
function env($key, $default = null) {
  return $_ENV[$key] ?? $default;
}

// Configuración de la base de datos usando variables de entorno
// $host = 'db';
$host = env('DB_HOST', 'localhost');
// $user = 'root';
$user = env('DB_USER', 'root');
// $password = 'rootpass';
$password = env('DB_PASSWORD', '');
// $dbname = 'appdb';
$dbname = env('DB_NAME', 'appdb');

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
}
?>
