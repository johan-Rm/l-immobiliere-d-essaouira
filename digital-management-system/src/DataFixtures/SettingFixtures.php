<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Organization;
use App\Entity\Person;
use App\Entity\RealEstateAgent;
use Doctrine\Common\Persistence\ObjectManager;

/** Fictitious organization and contacts, without personal production records. */
class SettingFixtures extends AbstractFixtures
{
    public function load(ObjectManager $manager)
    {
        $address = new Address();
        $address->setAddress('Adresse fictive');
        $address->setCity('Ville de demonstration');
        $address->setPostcode('00000');
        $address->setCountry('ZZ');
        $manager->persist($address);

        $organization = new Organization();
        $organization->setName('Agence Demo');
        $organization->setLegalName('Agence fictive');
        $organization->setPhone('');
        $organization->setUrl('http://localhost:3000');
        $organization->setEmail('contact@example.invalid');
        $organization->setFoundingDate(new \DateTime('2020-01-01'));
        $organization->setNumberOfEmployees(2);
        $organization->setNumberOfProjects(3);
        $organization->addAddress($address);
        $manager->persist($organization);

        foreach (['Alex', 'Sam'] as $firstname) {
            $person = new Person();
            $person->setFirstname($firstname);
            $person->setLastname('Demo');
            $person->setPhone('');
            $person->setEmail(strtolower($firstname).'@example.invalid');
            $manager->persist($person);

            $agent = new RealEstateAgent();
            $agent->setPerson($person);
            $agent->setPhone('');
            $agent->setEmail(strtolower($firstname).'@example.invalid');
            $agent->setDescription('Profil fictif de demonstration');
            $manager->persist($agent);
        }
        $manager->flush();
    }
}
