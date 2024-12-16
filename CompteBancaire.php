<?php
class CompteBancaire {
    private $numeroCompte;
    private $titulaire;
    private $solde;
    private $decouvertMaximal;
    private $debitMaximal;

    public function __construct($numeroCompte, Personne $titulaire, $solde, $decouvertMaximal = 0, $debitMaximal = 1000) {
        if ($solde < 0) {
            throw new InvalidArgumentException("Le solde initial ne peut pas être négatif.");
        }
        if ($decouvertMaximal < 0 || $debitMaximal < 0) {
            throw new InvalidArgumentException("Les limites de découvert et de débit doivent être positives.");
        }

        $this->numeroCompte = $numeroCompte;
        $this->titulaire = $titulaire;
        $this->solde = $solde;
        $this->decouvertMaximal = $decouvertMaximal;
        $this->debitMaximal = $debitMaximal;
    }

    public function getNumeroCompte() {
        return $this->numeroCompte;
    }

    public function getTitulaire() {
        return $this->titulaire;
    }

    public function setTitulaire(Personne $titulaire) {
        $this->titulaire = $titulaire;
    }

    public function getSolde() {
        return $this->solde;
    }

    public function getDecouvertMaximal() {
        return $this->decouvertMaximal;
    }

    public function setDecouvertMaximal($montant) {
        if ($montant < 0) {
            throw new InvalidArgumentException("Le découvert maximal doit être positif.");
        }
        $this->decouvertMaximal = $montant;
    }

    public function getDebitMaximal() {
        return $this->debitMaximal;
    }

    public function setDebitMaximal($montant) {
        if ($montant < 0) {
            throw new InvalidArgumentException("Le débit maximal doit être positif.");
        }
        $this->debitMaximal = $montant;
    }

    public function isDecouvert() {
        return $this->solde < 0;
    }

    public function montantRetirable() {
        return min($this->debitMaximal, $this->solde + $this->decouvertMaximal);
    }

    public function crediter($montant) {
        if ($montant <= 0) {
            throw new InvalidArgumentException("Le montant doit être positif.");
        }
        $this->solde += $montant;
    }

    public function debiter($montant) {
        if ($montant <= 0 || $montant > $this->debitMaximal || $this->solde - $montant < -$this->decouvertMaximal) {
            throw new InvalidArgumentException("Montant invalide ou limites dépassées.");
        }
        $this->solde -= $montant;
    }

    public function virer($montant, CompteBancaire $destinataire) {
        $this->debiter($montant);
        $destinataire->crediter($montant);
    }
}
?>
