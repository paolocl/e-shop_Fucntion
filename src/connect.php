<?php

    $pdo = new PDO('mysql:host=localhost;dbname=produits','root','troiswa');

    $connect = $pdo->exec('SET NAMES UTF8');
