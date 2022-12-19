<?php

declare(strict_types=1);

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
	<?php require 'inc/header.php' ?> 
	<main>
		<div class="category">
			<a href="/product/list">Produits</a> &gt; <?= $product->name ?>
		</div>
		<div id="detailProduct">
			<img src=<?= $imagePath ?> alt="<?= $product->name;?>" />
			<div>
				<div class="price"><?= $formattedPrice ?> €</div>
				<div class="category">catégorie<br />
					<?= $product->category?->name; ?></div>
				<div class="ref">référence<br />
					<?= $product->ref ?></div>
			</div>
		</div>
	</main>
	<footer></footer>
</body>

</html>