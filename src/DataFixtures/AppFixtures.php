<?php

namespace App\DataFixtures;

use App\Entity\Client;

use App\Entity\Restaurant;
use App\Entity\RestaurantFavoris;
use DateTimeInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use  Doctrine\Persistence\ObjectManager;

use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * Encodeur de mot de passe
     *
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    /**
     * Encodeur de mot de passe
     *
     * @var DateTimeInterface
     */
    private $dateTime;

    public function construct(DateTimeInterface $dateTime)
    {
        $this->dateTime = $dateTime;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $user = new Client();
        $user->setEmail('user@symfony.com')
            ->setPassword($this->encoder->encodePassword($user, 'password'));

        $manager->persist($user);

        $users[] = $user;
        for ($i = 0; $i < 20; $i++) {

            $user = new Client();
            $user->setEmail($faker->email)
                ->setPassword($this->encoder->encodePassword($user, 'password'));

            $manager->persist($user);
            $users[] = $user;
        }

        for ($i = 0; $i < 20; $i++) {
            $post = new Restaurant();
            $post->setNom($faker->sentence(6))
                ->setDescription($faker->paragraph())
->setHeureOuverture($faker->paragraph())
                ->setHeureFermeture("hhhhhhh")
                ->setAdresse('<p>' . join(',', $faker->paragraphs()) . '</p>');

            $manager->persist($post);
            for ($j = 0; $j < mt_rand(0, 10); $j++) {
                $like = new RestaurantFavoris();
                $like->setRestaurnant($post)
                    ->setClient($faker->randomElement($users));
                $manager->persist($like);
            }
        }

        $manager->flush();
    }


}