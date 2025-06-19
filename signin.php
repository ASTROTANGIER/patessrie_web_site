<?php
require 'db_pastrie.php'; // connexion PDO

session_start();

$emailError = $passwordError = "";
$errorGeneral = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    // Validation des champs
    if (empty($email)) {
        $emailError = "L'email est requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Format d'email invalide.";
    }

    if (empty($password)) {
        $passwordError = "Le mot de passe est requis.";
    }

    if (!$emailError && !$passwordError) {
        // Recherche admin dans la table admin
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE EMAIL = :email");
        $stmt->execute(['email' => $email]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['MODE_PASSE'])) {
            // Stocker les informations importantes dans la session
            $_SESSION['admin_id'] = $admin['ID_ADMIN'];
            $_SESSION['admin_name'] = $admin['NAME'];
            $_SESSION['admin_email'] = $admin['EMAIL'];
            $_SESSION['admin_role'] = $admin['ROLE'];
            $_SESSION['last_activity'] = time();
            
            header("Location: dashbord.php");
            exit;
        } else {
            $errorGeneral = "Email ou mot de passe incorrect.";
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

  <title>Connexion Admin - Pâtisserie</title>
  <style>
    /* CSS Pâtisserie + erreurs personnalisées */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background: linear-gradient(135deg, #fef7e0 0%, #f8f1d4 100%);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: #4b3b2b;
    }
    .login-card {
      padding: 2.5rem 3rem;
      border-radius: 20px;
      box-shadow: 0 10px 15px rgba(138, 134, 117, 0.77), 0 4px 6px rgba(203, 150, 155, 0.15);
      text-align: center;
      border: 3px solid #bd932e73;
      position: relative;
      width: 500px;
    }
    h2 {
      margin-bottom: 1.5rem;
      font-size: 2.5rem;
      color: #24292F;
      letter-spacing: 2px;
    }

    .form-group {
      margin-bottom: 1.25rem;
      text-align: left;
    }

    label {
      display: block;
      margin-bottom: 0.6rem;
      font-weight: 600;
      font-size: 0.95rem;
      color: #6d4c41;
    }

    input {
      width: 100%;
      padding: 0.75rem 1rem;
      font-size: 1rem;
      border-radius: 12px;
      border: 2px solid #bfac60;
      background-color: #fffbf4;
      transition: all 0.3s ease;
      color: #4b3b2b;
    }

    input::placeholder {
      color: #d9a7a7;
    }

    input:focus {
      outline: none;
      border-color: #a43e3e;
      box-shadow: 0 0 8px rgba(164, 62, 62, 0.7);
      background-color: #fff6f6;
    }

    /* Bordure rouge + ombre pour input en erreur */
    input.error-input {
      border-color: #d63447 !important;
      box-shadow: 0 0 5px rgba(214, 52, 71, 0.7);
      background-color: #fff0f0;
    }

    .btn {
      width: 100%;
      padding: 0.85rem;
      font-size: 1.15rem;
      border-radius: 16px;
      border: none;
      background: linear-gradient(135deg, #f1c40f, #e67e22);
      color: white;
      font-weight: 700;
      cursor: pointer;
      box-shadow: 0 5px 12px rgba(224, 122, 95, 0.6);
      transition: background 0.3s ease;
    }

    .btn:hover {
      background: linear-gradient(45deg, #f4a261, #e07a5f);
      box-shadow: 0 7px 16px rgba(244, 162, 97, 0.9);
    }

    margin-top: 1.2rem;
    .redirect {
      font-size: 14px;
      color: #6d4c41;
    }

    .redirect a {
      color: #a43e3e;
      font-weight: 600;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .redirect a:hover {
      text-decoration: underline;
      color: #f4a261;
    }

    /* Erreur générale */
    .error-general {
      color: #b02a37;
      background-color: #f8d7da;
      border: 1.5px solid #f5c6cb;
      padding: 10px 15px;
      margin-bottom: 1rem;
      border-radius: 8px;
      font-weight: 700;
      text-align: center;
      box-shadow: 0 0 8px rgba(176, 42, 55, 0.3);
    }

    /* Erreur spécifique champ */
    .error {
      color: #d63447;
      font-size: 13px;
      margin-top: 0.3rem;
      margin-bottom: 0.75rem;
      font-weight: 600;
      font-style: italic;
    }
  </style>
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
  <div class="container">
    <div class="login-card">
      <h2>Connexion Administration</h2>

      <?php if ($errorGeneral): ?>
        <div class="error-general"><?= htmlspecialchars($errorGeneral) ?></div>
      <?php endif; ?>

      <form action="" method="POST" autocomplete="off" novalidate>
        <div class="form-group">
          <label for="email">Email</label>
          <input
            type="email"
            id="email"
            name="email"
            placeholder="Entrez votre email"
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
            placeholder="Entrez votre mot de passe"
            class="<?= $passwordError ? 'error-input' : '' ?>"
          >
          <?php if ($passwordError): ?>
            <div class="error"><?= $passwordError ?></div>
          <?php endif; ?>
        </div>

        <button type="submit" class="btn">Se connecter</button>
      </form>
    </div>
  </div>
</body>
</html>
