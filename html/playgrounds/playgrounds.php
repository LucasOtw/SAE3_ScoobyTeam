<?php
use OTPHP\TOTP;

$clock = new MyClock(); // Your own implementation of a PSR-20 Clock

// A random secret will be generated from this.
// You should store the secret with the user for verification.
$otp = TOTP::generate($clock);
echo "The OTP secret is: {$otp->getSecret()}\n";

// Note: use your own way to load the user secret.
// The function "load_user_secret" is simply a placeholder.
$secret = load_user_secret();
$otp = TOTP::createFromSecret($secret, $clock);
echo "The current OTP is: {$otp->now()}\n";

?>