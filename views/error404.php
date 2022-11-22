<?php

declare(strict_types=1);

namespace vues;

use peps\core\Cfg;

?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<meta charset="UTF-8">
	<title><?= Cfg::get('appTitle') ?></title>
	<link rel="stylesheet" href="/assets/css/acme.css" />
</head>

<body>
	<header></header>
	<main>
		<div class="category">
			<a href="/listProducts.php">Accueil</a> &gt; Oups !
		</div>
		<img src="/test/3" alt="Oups !" />
	</main>
	<footer></footer>
</body>

</html>