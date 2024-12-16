<?php
class Personne {
    private $nom;
    private $prenom;
    private $adresse;

    public function __construct($nom, $prenom, $adresse) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->adresse = $adresse;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getAdresse() {
        return $this->adresse;
    }

    public function __toString() {
        return $this->prenom . " " . $this->nom . " (" . $this->adresse . ")";
    }
}
?>
