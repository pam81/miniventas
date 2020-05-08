<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Services\UserService;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Services\AccountService;

class UserFixtures extends Fixture implements DependentFixtureInterface
{

    private $passwordEncoder;
    private $userService;
    private $accountService;

     public function __construct(UserPasswordEncoderInterface $passwordEncoder, 
     UserService $userService, AccountService $accountService)
     {
         $this->passwordEncoder = $passwordEncoder;
         $this->userService = $userService;
         $this->accountService = $accountService;
     }

     public function getDependencies()
    {
        return [
            AccountFixtures::class
        ];
    }


    public function load(ObjectManager $manager)
    {
        $account = $this->accountService->findOneBy(['name' => 'Amauta']);

        $users = [
            [
                'name' => 'Pam',
                'lastname' => 'Garcia',
                'email' => 'amauta@amautasoft.com',
                'roles' => ['ROLE_ADMIN'],
                'password' => 'admin687',
                'account'  => $account
            ],
            [
                'name' => 'Test',
                'lastname' => 'Test',
                'email' => 'demo@amautasoft.com',
                'roles' => ['ROLE_USER'],
                'password' => 'demos687'
            ],
            
          ];
        $this->buildUsers($users);
    }

    private function buildUsers($users)
    {
        foreach ($users as $data) {
            $user = $this->userService->findOneBy(['email' => $data['email']]);
            if (!$user){
                $this->userService->create($data);
            }
        }
    }
}
