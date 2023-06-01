<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="/img/webylo.ico" type="image/x-icon" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link href="/css/styles.css" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.21.3/dist/bootstrap-table.min.css">
  <title><?= $title ?></title>
</head>

<body>
  <nav class="navbar fixed-top bg-body-tertiary">
    <div class="container-fluid">
      <?php if (isset($_SESSION['user'])) { ?>
        <a href="/candidat/" class="navbar-brand"><img src="/img/logo.png" class="logo-header" alt="logo_webylo"></a>
      <?php } else { ?>
        <a href="https://recrutement.webylo.fr" class="navbar-brand"><img src="/img/logo.png" class="logo-header" alt="logo_webylo"></a>
      <?php } ?>
      <div class="d-flex align-items-center">
        <?php if (isset($_SESSION['user'])) { ?>
          <h5><a href="/utilisateur/<?= $_SESSION['id'] ?>/" class="text-decoration-none text-black my-3"><?= $_SESSION['user']->getNom() ?> <?= $_SESSION['user']->getPrenom() ?></a></h5>
          <a href="/candidat/" class="btnBlue m-3">Dashboard</a>
          <a href="/deconnexion/" class="btnPink">Se déconnecter</a>
        <?php } else { ?>
          <a href="https://webylo.fr/" class="btnBlue">Webylo</a>
        <?php } ?>
      </div>
    </div>
  </nav>
</body>

</html>