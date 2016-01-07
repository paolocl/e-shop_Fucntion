<?php

    $pdo = new PDO('mysql:host=localhost;dbname=produits','root','troiswa', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

    $connect = $pdo->exec('SET NAMES UTF8');
