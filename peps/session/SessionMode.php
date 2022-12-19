<?php

declare(strict_types=1);

namespace peps\session;

/**
 * Enumération des modes de session.
 * 
 *   PERSISTENT: La session se termine exclusivement après l'expiration du timeout au-delà de la dernière requête du client.
 *   HYBRID: La session se termine à la fermeture du navigateur OU après l'expiration du timeout au-delà de la dernière requête du client.
 *   ABSOLUTE: La session se termine exclusivement après l'expiration du timeout au-delà de la PREMIERE requête du client.
 */
enum SessionMode {
    case PERSISTENT;
    case HYBRID;
    case ABSOLUTE;
}