  <?php
  require 'db_pastrie.php'; 

  session_start();

  $name = $email = $password = $confirm_password = $role = "";
  $nameError = $emailError = $passwordError = $confirmPasswordError = $roleError = "";
  $errorGeneral = "";

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $name = trim($_POST['name'] ?? "");
      $email = trim($_POST['email'] ?? "");
      $password = $_POST['password'] ?? "";
      $confirm_password = $_POST['confirm_password'] ?? "";
      $role = $_POST['role'] ?? "";

      // Validation
      if (empty($name)) {
          $nameError = "Le nom est requis.";
      } elseif (strlen($name) < 3) {
          $nameError = "Le nom doit contenir au moins 3 caractères.";
      }

      if (empty($email)) {
          $emailError = "L'email est requis.";
      } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $emailError = "Format d'email invalide.";
      }

      if (empty($password)) {
          $passwordError = "Le mot de passe est requis.";
      } elseif (strlen($password) < 6) {
          $passwordError = "Le mot de passe doit contenir au moins 6 caractères.";
      }

      if (empty($confirm_password)) {
          $confirmPasswordError = "Veuillez confirmer le mot de passe.";
      } elseif ($password !== $confirm_password) {
          $confirmPasswordError = "Les mots de passe ne correspondent pas.";
      }

      if (empty($role)) {
          $roleError = "Le rôle est requis.";
      } elseif (!in_array($role, ['admin', 'manager', 'user'])) {
          $roleError = "Rôle invalide sélectionné.";
      }

      if (!$nameError && !$emailError && !$passwordError && !$confirmPasswordError && !$roleError) {
          $stmt = $pdo->prepare("SELECT * FROM admin WHERE EMAIL = :email");
          $stmt->execute(['email' => $email]);
          if ($stmt->fetch()) {
              $emailError = "Cet email est déjà enregistré.";
          } else {
              $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
              $stmt = $pdo->prepare("INSERT INTO admin (NAME, EMAIL, MODE_PASSE, ROLE) VALUES (:name, :email, :password, :role)");
              try {
                  $stmt->execute([
                      'name' => $name,
                      'email' => $email,
                      'password' => $hashedPassword,
                      'role' => $role
                  ]);
                  header("Location: signin.php");
                  exit;
              } catch (PDOException $e) {
                  $errorGeneral = "Erreur lors de l'inscription: " . $e->getMessage();
              }
          }
      }
  }
  ?>

  <!DOCTYPE html>
  <html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
      <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Inscription Admin - Pâtisserie</title>
    <style>
      * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  body {
   
    background: linear-gradient(135deg, #fef7e0 0%, #f8f1d4 100%);;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }

  /* .container {
    width: 100%;
    max-width: 400px;
  } */

  .signup-card {
    width: 800px;
    padding: 2.5rem 3rem;
  border-radius: 20px;
  box-shadow: 0 10px 15px rgba(138, 134, 117, 0.77), 0 4px 6px rgba(203, 150, 155, 0.15);
  text-align: center;
  position: relative;
  border: 3px solid #bd932e73;

  }

  .signup-card h2 {
    text-align: center;
      font-size: 24px;
    color: #374151;
    margin-bottom: 24px;
  }

  .form-group {
    margin-bottom: 24px;
  }

  .form-group label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 8px;
  }

  .form-group input,
  .form-group select {
    width: 100%;
    height: 48px;
    padding: 12px 16px;
    background-color: #f8f0f8;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 16px;
    color: #374151;
    transition: border-color 0.2s, box-shadow 0.2s;
  }

  .form-group input::placeholder {
    color: #9ca3af;
  }

  .form-group input:focus,
  .form-group select:focus {
    outline: none;
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
  }

  .error {
    font-size: 14px;
    color: #dc2626;
    font-style: italic;
    margin-top: 4px;
  }

  .error-general {
    font-size: 14px;
    color: #dc2626;
    text-align: center;
    margin-bottom: 16px;
  }

  .error-input {
    border-color: #dc2626 !important;
  }

  .btn {
    width: 100%;
    height: 48px;
    background: linear-gradient(135deg, #f59e0b 0%, #f97316 100%);
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 18px;
    font-weight: 500;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.2s, transform 0.1s;
  }

  .btn:hover {
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-1px);
  }

  .btn:active {
    transform: translateY(0);
  }

  .redirect {
    margin-top: 16px;
    text-align: center;
    font-size: 14px;
    color: #374151;
  }

  .redirect a {
    color: #f59e0b;
    text-decoration: none;
    font-weight: 600;
  }

  .redirect a:hover {
    text-decoration: underline;
  }

  @media (max-width: 768px) {
      .signup-card {
        width: 100%;
        padding: 20px;
      }

      .signup-card h2 {
        font-size: 20px;
      }

      .form-group input,
      .form-group select {
        height: 36px;
        padding: 8px;
      }

      .btn {
        height: 36px;
        font-size: 14px;
      }
  }

    </style>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
  </head>
  <body>
    <div class="container">
      <div class="signup-card">
        <h2>Inscription Admin</h2>

        <?php if ($errorGeneral): ?>
          <div class="error-general"><?= htmlspecialchars($errorGeneral) ?></div>
        <?php endif; ?>

        <form action="" method="POST" autocomplete="off" novalidate>
          <div class="form-group">
            <label for="name">Nom complet</label>
            <input
              type="text"
              id="name"
              name="name"
              placeholder="Votre nom complet"
              value="<?= htmlspecialchars($name) ?>"
              class="<?= $nameError ? 'error-input' : '' ?>"
            >
            <?php if ($nameError): ?>
              <div class="error"><?= $nameError ?></div>
            <?php endif; ?>
          </div>

          <div class="form-group">
            <label for="email">Email</label>
            <input
              type="email"
              id="email"
              name="email"
              placeholder="Votre adresse email"
              value="<?= htmlspecialchars($email) ?>"
              class="<?= $emailError ? 'error-input' : '' ?>"
            >
            <?php if ($emailError): ?>
              <div class="error"><?= $emailError ?></div>
            <?php endif; ?>
          </div>

          <div class="form-group">
            <label for="password">Mot de passe</label>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Votre mot de passe"
              class="<?= $passwordError ? 'error-input' : '' ?>"
            >
            <?php if ($passwordError): ?>
              <div class="error"><?= $passwordError ?></div>
            <?php endif; ?>
          </div>

          <div class="form-group">
            <label for="confirm_password">Confirmer le mot de passe</label>
            <input
              type="password"
              id="confirm_password"
              name="confirm_password"
              placeholder="Confirmez le mot de passe"
              class="<?= $confirmPasswordError ? 'error-input' : '' ?>"
            >
            <?php if ($confirmPasswordError): ?>
              <div class="error"><?= $confirmPasswordError ?></div>
            <?php endif; ?>
          </div>

          <div class="form-group">
            <label for="role">Rôle</label>
            <select
              id="role"
              name="role"
              class="<?= $roleError ? 'error-input' : '' ?>"
            >
              <option value="" disabled <?= $role === "" ? "selected" : "" ?>>Sélectionnez un rôle</option>
              <option value="admin" <?= $role === "admin" ? "selected" : "" ?>>Admin</option>
              <option value="manager" <?= $role === "manager" ? "selected" : "" ?>>Manager</option>
              <option value="user" <?= $role === "user" ? "selected" : "" ?>>Semi-admin</option>
            </select>
            <?php if ($roleError): ?>
              <div class="error"><?= $roleError ?></div>
            <?php endif; ?>
          </div>

          <button type="submit" class="btn">S'inscrire</button>
        </form>

        <p class="redirect">Déjà un compte? <a href="signin.php">Se connecter</a></p>
      </div>
    </div>
  </body>
  </html>
