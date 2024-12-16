<?php
use PHPUnit\Framework\TestCase;

require("Personne.php");
require("CompteBancaire.php");


class CompteTest extends TestCase
{
    public function testCreationCompteAvecSoldeInitial() {
        $personne = new Personne("Martin", "Éric", "");
        $compte = new CompteBancaire(123, $personne, 5000);
        $this->assertEquals(5000, $compte->getSolde());
    }

    public function testCreationCompteAvecDecouvertMaximal() {
        $personne = new Personne("Berlioz", "Le Du", "");
        $compte = new CompteBancaire(123, $personne, 0, 3000);
        $this->assertEquals(3000, $compte->getDecouvertMaximal());
    }

    public function testCreationCompteAvecDebitMaximal() {
        $personne = new Personne("Julie", "Durand", "");
        $compte = new CompteBancaire(456, $personne, 1000, 2000, 500);
        $this->assertEquals(500, $compte->getDebitMaximal());
    }

    public function testCreditCompte() {
        $personne = new Personne("Eric", "Nopon", "");
        $compte = new CompteBancaire(123, $personne, 600);
        $compte->crediter(50);
        $this->assertEquals(650, $compte->getSolde());
    }

    public function testDebitCompteValide() {
        $personne = new Personne("Martin", "Éric", "");
        $compte = new CompteBancaire(123, $personne, 5000);
        $compte->debiter(400);
        $this->assertEquals(4600, $compte->getSolde());
    }

    public function testDebitCompteDepasseDebitMaximal() {
        $personne = new Personne("Eric", "Nopon", "");
        $compte = new CompteBancaire(123, $personne, 600, 500, 300);
        $this->expectException(InvalidArgumentException::class);
        $compte->debiter(400); // Excède le débit maximal
    }

    public function testDebitCompteDepasseDecouvertMaximal() {
        $personne = new Personne("Marie", "Durand", "");
        $compte = new CompteBancaire(456, $personne, 1000, 500, 300);
        $this->expectException(InvalidArgumentException::class);
        $compte->debiter(1600); // Excède le découvert autorisé
    }

    public function testMontantRetirableRespecteDebitMaximal() {
        $personne = new Personne("Adélaïde", "Bonnefamille", "");
        $compte = new CompteBancaire(123, $personne, 350000, 0, 100000);
        $this->assertEquals(100000, $compte->montantRetirable());
    }

    public function testMontantRetirableRespecteDecouvertMaximal() {
        $personne = new Personne("Paul", "Smith", "");
        $compte = new CompteBancaire(789, $personne, 500, 1000, 2000);
        $this->assertEquals(1500, $compte->montantRetirable()); // 500 + 1000 de découvert
    }

    public function testVirementValide() {
        $personne1 = new Personne("Martin", "Éric", "");
        $personne2 = new Personne("Nopon", "Éric", "");
        $compte1 = new CompteBancaire(123, $personne1, 5000);
        $compte2 = new CompteBancaire(124, $personne2, 600);
        
        $compte1->virer(1500, $compte2);
        $this->assertEquals(3500, $compte1->getSolde());
        $this->assertEquals(2100, $compte2->getSolde());
    }

    public function testVirementDepasseDecouvertMaximal() {
        $personne1 = new Personne("Jean", "Dupont", "");
        $personne2 = new Personne("Pierre", "Durand", "");
        $compte1 = new CompteBancaire(123, $personne1, 1000, 500);
        $compte2 = new CompteBancaire(124, $personne2, 600);
        
        $this->expectException(InvalidArgumentException::class);
        $compte1->virer(2000, $compte2); // Excède le découvert
    }

    public function testModificationTitulaire() {
        $personne = new Personne("Marie", "de Bonnefamille", "");
        $compte = new CompteBancaire(123, $personne, 1000);
        $nouveauTitulaire = new Personne("Marie", "de Bonnefamille O'Malley", "");
        $compte->setTitulaire($nouveauTitulaire);
        $this->assertEquals("Marie", $compte->getTitulaire()->getPrenom());
    }

    public function testModificationDecouvertMaximal() {
        $personne = new Personne("Alice", "Miller", "");
        $compte = new CompteBancaire(123, $personne, 1000, 500);
        $compte->setDecouvertMaximal(800);
        $this->assertEquals(800, $compte->getDecouvertMaximal());
    }

    public function testModificationDebitMaximal() {
        $personne = new Personne("Bob", "Smith", "");
        $compte = new CompteBancaire(123, $personne, 1000, 500, 300);
        $compte->setDebitMaximal(600);
        $this->assertEquals(600, $compte->getDebitMaximal());
    }

    public function testCompteEnDecouvert() {
        $personne = new Personne("Sarah", "Connor", "");
        $compte = new CompteBancaire(123, $personne, -100, 500);
        $this->assertTrue($compte->isDecouvert());
    }

    public function testCompteNonEnDecouvert() {
        $personne = new Personne("John", "Doe", "");
        $compte = new CompteBancaire(123, $personne, 100, 500);
        $this->assertFalse($compte->isDecouvert());
    }

    public function testDebitMontantNegatif() {
        $personne = new Personne("Emma", "Stone", "");
        $compte = new CompteBancaire(123, $personne, 1000);
        $this->expectException(InvalidArgumentException::class);
        $compte->debiter(-500);
    }

    public function testCreditMontantNegatif() {
        $personne = new Personne("Liam", "Neeson", "");
        $compte = new CompteBancaire(123, $personne, 1000);
        $this->expectException(InvalidArgumentException::class);
        $compte->crediter(-200);
    }

    public function testNumeroCompteCorrect() {
        $personne = new Personne("Eve", "Adams", "");
        $compte = new CompteBancaire(987654, $personne, 3000);
        $this->assertEquals(987654, $compte->getNumeroCompte());
    }

    public function testNumeroCompteUnique() {
        $personne1 = new Personne("Henry", "Ford", "");
        $personne2 = new Personne("Nikola", "Tesla", "");
        $compte1 = new CompteBancaire(123, $personne1, 1000);
        $compte2 = new CompteBancaire(124, $personne2, 2000);
        $this->assertNotEquals($compte1->getNumeroCompte(), $compte2->getNumeroCompte());
    }
}
?>
