<?php
require( 'Money.php');
require( 'Personne.php');
require( 'CompteBancaire.php');

$personne = new Personne("Kowalski", "Damien", "adresse");
$compte = new CompteBancaire(1234, $personne, 1000);
echo $personne;
echo $compte;
?>