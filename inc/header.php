<?php

namespace inc;

use entities\User;

?>

<header>
	<div class="user">
		<?php
		if(User::getLoggedUser()) {
			?>
			<?= User::getLoggedUser()?->lastName; ?> <?= User::getLoggedUser()->firstName; ?>
			<a href="/logout.php">Déconnexion</a>
		<?php
		} else {
		?>
			<a href="/signin.php">Connexion</a>
		<?php
		}
		?>
	</div>
</header>