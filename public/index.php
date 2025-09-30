<?php
// public/index.php

require_once __DIR__ . '/db.php'; // usa tu conexión PDO ($pdo)

// Cargas manuales (sin Composer) según tu estructura actual
require_once __DIR__ . '/../src/Domain/Entities/User.php';
require_once __DIR__ . '/../src/Domain/ValueObjects/Email.php';
require_once __DIR__ . '/../src/Domain/Services/UserService.php';
require_once __DIR__ . '/../src/Infrastructure/Persistence/UserRepository.php';   // interfaz
require_once __DIR__ . '/../src/Infrastructure/Adapters/UserRepositoryMySQL.php'; // implementación

use Domain\Entities\User;
use Domain\ValueObjects\Email;
use Domain\Services\UserService;
use Infrastructure\Adapters\UserRepositoryMySQL;

// Repositorio + Servicio
$repo    = new UserRepositoryMySQL($pdo);
$service = new UserService($repo);

// Mensaje para feedback
$msg = null;

// Procesar formulario (crear usuario)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']  ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($name === '' || $email === '') {
        $msg = '⚠️ Debes ingresar nombre y correo.';
    } else {
        try {
            $user = new User($name, new Email($email)); // ajusta si tu User recibe string en vez de VO
            $service->register($user);
            $msg = '✅ Usuario registrado correctamente.';
        } catch (Throwable $e) {
            $msg = '⚠️ Error al registrar usuario: ' . htmlspecialchars($e->getMessage());
        }
    }
}

// Listar usuarios
$users = $service->listUsers();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>CEA-PHP · Usuarios</title>
</head>
<body>
  <h1>Registro de usuarios</h1>

  <?php if ($msg): ?>
    <p><?= $msg ?></p>
  <?php endif; ?>

  <form method="post">
    <label>Nombre</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <button type="submit">Registrar</button>
  </form>

  <h2>Lista de usuarios</h2>
  <?php if (empty($users)): ?>
    <p>No hay usuarios registrados.</p>
  <?php else: ?>
    <ul>
      <?php foreach ($users as $u): ?>
        <?php
          // Soporte si tu repositorio devuelve entidades o arrays:
          if (is_object($u)) {
            $id    = method_exists($u, 'getId') ? $u->getId() : '';
            $name  = method_exists($u, 'getName') ? $u->getName() : '';
            $emailObjOrStr = method_exists($u, 'getEmail') ? $u->getEmail() : '';
            $email = is_object($emailObjOrStr) && method_exists($emailObjOrStr, 'getValue')
                    ? $emailObjOrStr->getValue()
                    : (string)$emailObjOrStr;
          } else {
            // array asociativo
            $id    = $u['id']    ?? '';
            $name  = $u['name']  ?? '';
            $email = $u['email'] ?? '';
          }
        ?>
        <li><?= htmlspecialchars((string)$id) ?> — <?= htmlspecialchars((string)$name) ?>
            (<?= htmlspecialchars((string)$email) ?>)
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</body>
</html>
