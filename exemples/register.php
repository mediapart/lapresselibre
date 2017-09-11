<?php

require 'vendor/autoload.php';

use Mediapart\LaPresseLibre\Security\Encryption;
use Mediapart\LaPresseLibre\Registration;

$public_key = 2;
$encryption = new Encryption('UKKzV7sxiGx3uc0auKrUO2kJTT2KSCeg', '7405589013321961');
$registration = new Registration($public_key, $encryption);

$link = $registration->generateLink('username@domain.tld', 'username');

echo '<a href="'.$link.'">register into La Presse Libre</a>';
