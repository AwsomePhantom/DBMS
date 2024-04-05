<?php

    echo uniqid('user_', true) . '<br>';

    $bytes = openssl_random_pseudo_bytes(16);
    echo strtoupper(bin2hex($bytes))  . '<br>';

    $unique_id = md5(uniqid(mt_rand(), true));
    echo strtoupper($unique_id)  . '<br>';

