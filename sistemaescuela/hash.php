```php
<?php

$hash = '$2y$10$araNhp1OYezveFwEdD1v2eb.LIzqqFGyYIygv6FfytFwzsmBAB31u';

if (password_verify('123456', $hash)) {
    echo "CORRECTO: el hash corresponde a 123456";
} else {
    echo "INCORRECTO: ese hash NO corresponde a 123456";
}

?>