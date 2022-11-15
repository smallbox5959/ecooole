<?php

namespace App\DataFixtures;

use App\Entity\Prof;
use App\Entity\User;
use App\Entity\Eleve;
use App\Entity\Classe;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {   
        // Création d'un user "normal"
        $user = new User();
        $user->setEmail("user@ecoleapi.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
        $manager->persist($user);

        // Création d'un user admin
        $userAdmin = new User();
        $userAdmin->setEmail("admin@ecoleapi.com");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "password"));
        $manager->persist($userAdmin);


        $listProf = [];
        for ($i = 0; $i < 10; $i++) {
            // Création de l'auteur lui-même.
            $prof = new Prof();
            $prof->setPrenom("Prénom " . $i);
            $prof->setNom("Nom " . $i);
            $manager->persist($prof);
            // On sauvegarde l'auteur créé dans un tableau.
            $listProf[] = $prof;
        }
        // $product = new Product();
        // $manager->persist($product);
        // Création d'une vingtaine de livres ayant pour titre
        for ($i = 0; $i < 20; $i++) {
            $eleve = new Eleve;
            $eleve->setNom('Eleve ' . $i);
            $eleve->setMoyenne(rand(0,20));
            $eleve->setProf($listProf[array_rand($listProf)]);
            $manager->persist($eleve);           
    }

        for ($i = 0; $i < 3; $i++) {
        $classe = new Classe;
        $classe->setNom('Classe ' . $i);
        $classe->setProf($listProf[array_rand($listProf)]);
        $manager->persist($classe);           
    }

    

    
$manager->flush();
}
}
