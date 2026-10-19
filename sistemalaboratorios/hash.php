<?php

$hash = '$2y$10$araNhp1OYezveFwEdD1v2eb.LIzqqFGyYIygv6FfytFwzsmBAB31u';

if (password_verify('12345678', $hash)) {
    echo "CORRECTO: el hash corresponde a 12345678";
} else {
    echo "INCORRECTO: ese hash NO corresponde a 12345678";
}

?>
