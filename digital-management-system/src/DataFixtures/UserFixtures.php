<?php

namespace App\DataFixtures;

use App\Entity\Person;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/** A fictitious development account, with an explicitly supplied password. */
class UserFixtures extends AbstractFixtures
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        if ($this->container->getParameter('kernel.environment') !== 'dev') {
            throw new \RuntimeException('Demo users may only be created in development.');
        }
        $password = $this->container->getParameter('demo_admin_password');
        if (!is_string($password) || strlen($password) < 16) {
            throw new \RuntimeException('Set DEMO_ADMIN_PASSWORD to a unique password of at least 16 characters.');
        }
        $person = new Person();
        $person->setFirstname('Alex');
        $person->setLastname('Demo');
        $person->setEmail('admin@example.invalid');
        $manager->persist($person);

        $user = new User();
        $user->setUsername('demo-admin');
        $user->setEmail('admin@example.invalid');
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $user->setEnabled(true);
        $user->setPerson($person);
        $manager->persist($user);
        $manager->flush();
    }
}
