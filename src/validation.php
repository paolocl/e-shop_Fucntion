<?php

	$connection = 'ko';

    $pdo = new PDO('mysql:host=localhost;dbname=produits','root','troiswa', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

   $pdo->exec('SET NAMES UTF8');

		$query = $pdo->prepare("
					SELECT login, password
					FROM admin
				");	

				$query->execute();

				$login = $query->fetchAll(PDO::FETCH_ASSOC);
				//var_dump($listCommande);

				if(isset($_POST['submit']) && isset($_POST['login']) && isset($_POST['password']) && !empty($_POST['login']) && !empty($_POST['password']))
				{
					foreach($login as $unLogeur)
					{
						if($unLogeur['login'] === $_POST['login'] && $unLogeur['password'] === $_POST['password'])
						{
							$_SESSION['login'] = $_POST['login'];
							$_SESSION['password'] = $_POST['password'];	
							$connection = 'ok';
						}
					}
				}
				elseif(isset($_SESSION['password']) && isset($_SESSION['login']) && !empty($_SESSION['password']) && !empty($_SESSION['login']))
				{
					foreach($login as $unLogeur)
					{
						if($unLogeur['login'] === $_SESSION['login'] && $unLogeur['password'] === $_SESSION['password'])
						{
							$connection = 'ok';
						}
					}
				}