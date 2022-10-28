<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $faker;
    private $hasher;
    private $images;
    private $parameter;
    const STATUS = ['IN_PROGRESS', 'DONE', 'BLOCKED'];
    public function __construct(UserPasswordHasherInterface $passwordHasher, ParameterBagInterface $parameter)
    {
        $this->parameter = $parameter;
        $this->hasher    = $passwordHasher;
        $this->faker     = \Faker\Factory::create();
        $this->images    = [1, 2, 3, 4, 5, 6, 7];
    }
    public function load(ObjectManager $manager): void
    {

        // load admin user
        $admin = new User();
        $admin->setFirstName('SoftToDo')
            ->setLastName('Admin')
            ->setEmail('softtodo@technical.com')
            ->setPassword($this->hasher->hashPassword($admin, 'secret123'))
            ->setRole('ROLE_ADMIN');
        $manager->persist($admin);

        // load some fake normal users
        for ($i = 0; $i < 10; $i++) {
            $normal = new User();
            $normal->setFirstName($this->faker->firstName)
                ->setLastName($this->faker->lastName)
                ->setEmail($this->faker->email)
                ->setPassword($this->hasher->hashPassword($normal, 'secret123'))
                ->setRole('ROLE_NORMAL');
            $manager->persist($normal);
        }

        // load some fake projects
        for ($i = 0; $i <= 20; $i++) {
            $path     = $this->parameter->get('kernel.project_dir');
            $filename = "file_$i.txt";
            $f = fopen($path . "/public/files/$filename", "w+");
            fwrite($f, $this->faker->word(10));
            $randIndex = rand(0, 6);
            $imageName = $this->images[$randIndex] . '.jpg';
            $project = new Project();
            $project->setTitle("Title Project $i")
                ->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed")
                ->setFilenameOrUrl("files/$filename")
                ->setImage("images/$imageName")
                ->setStatus(self::STATUS[mt_rand(0, 2)])
                ->setNumberOfTasks(mt_rand(1, 10));
            $manager->persist($project);
        }
        $manager->flush();
    }
}