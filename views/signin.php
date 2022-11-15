<?php

declare(strict_types=1);

use classes\Autoload;
use entities\User;

require './classes/Autoload.php';
// Initialisation de l'autoload
Autoload::init();

// Ouverture de la session utilisateur
session_start();

// Créer un user
$user = new User();

// Initialiser le tableau des erreurs
$errors = [];
$submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_SPECIAL_CHARS);
if($submit) {
	// Récupérer les données et tenter le login
	$user->log = filter_input(INPUT_POST, 'log', FILTER_SANITIZE_SPECIAL_CHARS);
	$pwd = filter_input(INPUT_POST, 'pwd', FILTER_SANITIZE_SPECIAL_CHARS);
	// Si login 
	if ($user->login($pwd)) {
		header('Location:listProducts.php');
		exit("Redirection: login OK");
	}
	$errors[] = User::ERROR_LOGIN_FAILED;
}


?>
<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<title>ACME</title>
	<link rel="stylesheet" href="/assets/css/acme.css" />
</head>

<body>
	<header></header>
	<main>
		<div class="category">
			<a href="/listProducts.php">Accueil</a> &gt; Connexion
		</div>
		<div class="error"><?= implode('<br/>', $errors ?: []) ?></div>
		<form name="form1" action="/signin.php" method="POST">
			<div class="item">
				<label>Identifiant</label>
				<input name="log" value="<?= $user->log ?>" size="10" maxlength="10" />
			</div>
			<div class="item">
				<label>Mot de passe</label>
				<input type="password" name="pwd" size="10" maxlength="10" />
			</div>
			<div class="item">
				<label></label>
				<input name="submit" type="submit" value="Connexion" />
			</div>
		</form>
	</main>
	<footer></footer>
</body>

</html>