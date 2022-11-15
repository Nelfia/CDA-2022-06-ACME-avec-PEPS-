<?php

declare(strict_types=1);

use classes\Autoload;

require './classes/Autoload.php';
// Initialisation de l'autoload
Autoload::init();

// Ouverture de la session utilisateur
session_start();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<title>ACME</title>
	<link rel="stylesheet" href="/assets/css/acme.css" />
</head>

<body>
	<?php require 'inc/header.php' ?>
	<main>
		<div class="category">
			<a href="/listProducts.php">Accueil</a> &gt; Oups !
		</div>
		<img src="/assets/img/error404.png" alt="Oups !" />
	</main>
	<footer></footer>
</body>

</html>