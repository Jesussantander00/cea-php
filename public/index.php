<?php
// public/index.php

require_once __DIR__ . '/db.php'; // usa tu conexión PDO ($pdo)

// Cargas manuales (sin Composer)
require_once __DIR__ . '/../src/Domain/Entities/User.php';
require_once __DIR__ . '/../src/Domain/ValueObjects/Email.php';
require_once __DIR__ . '/../src/Domain/Services/UserService.php';
require_once __DIR__ . '/../src/Infrastructure/Persistence/UserRepository.php';
require_once __DIR__ . '/../src/Infrastructure/Adapters/UserRepositoryMySQL.php';

use Domain\Entities\User;
use Domain\ValueObjects\Email;
use Domain\Services\UserService;
use Infrastructure\Adapters\UserRepositoryMySQL;

// Repositorio + Servicio
$repo    = new UserRepositoryMySQL($pdo);
$service = new UserService($repo);

// Mensaje
$msg = null;

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($name === '' || $email === '') {
        $msg = '⚠️ Debes ingresar nombre y correo.';
    } else {
        try {
            $user = new User($name, new Email($email));
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
  <style>
    body {
      font-family: Arial, sans-serif;
      text-align: center;
      color: #fff;
      background-image: url('https://images.unsplash.com/photo-1521790361600-2e0a8c1b6a09?auto=format&fit=crop&w=1400&q=80');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      margin: 0;
      padding: 0;
    }

    .container {
      background-color: rgba(0, 0, 0, 0.7);
      margin: 50px auto;
      padding: 30px;
      border-radius: 15px;
      width: 80%;
      max-width: 600px;
    }

    h1 {
      color: #00bcd4;
    }

    form {
      margin-top: 20px;
    }

    input {
      width: 80%;
      padding: 10px;
      margin: 5px 0;
      border: none;
      border-radius: 5px;
    }

    button {
      background-color: #00bcd4;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 10px;
    }

    button:hover {
      background-color: #0097a7;
    }

    ul {
      list-style: none;
      padding: 0;
    }

    li {
      background-color: rgba(255, 255, 255, 0.1);
      padding: 10px;
      margin: 5px 0;
      border-radius: 5px;
    }

    p {
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Registro de usuarios</h1>

    <?php if ($msg): ?>
      <p><?= $msg ?></p>
    <?php endif; ?>

    <form method="post">
      <input type="text" name="name" placeholder="Nombre completo" required><br>
      <input type="email" name="email" placeholder="Correo electrónico" required><br>
      <button type="submit">Registrar</button>
    </form>

    <h2>Lista de usuarios</h2>
    <?php if (empty($users)): ?>
      <p>No hay usuarios registrados.</p>
    <?php else: ?>
      <ul>
        <?php foreach ($users as $u): ?>
          <?php
            if (is_object($u)) {
              $id    = method_exists($u, 'getId') ? $u->getId() : '';
              $name  = method_exists($u, 'getName') ? $u->getName() : '';
              $emailObjOrStr = method_exists($u, 'getEmail') ? $u->getEmail() : '';
              $email = is_object($emailObjOrStr) && method_exists($emailObjOrStr, 'getValue')
                      ? $emailObjOrStr->getValue()
                      : (string)$emailObjOrStr;
            } else {
              $id    = $u['id']    ?? '';
              $name  = $u['name']  ?? '';
              $email = $u['email'] ?? '';
            }
          ?>
          <li><?= htmlspecialchars($id) ?> — <?= htmlspecialchars($name) ?> (<?= htmlspecialchars($email) ?>)</li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>
</body>
</html>
