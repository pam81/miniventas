<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use App\Services\AccountService;

class  AccountFixtures extends Fixture implements ContainerAwareInterface
{
    private $container;

    private $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    public function load(ObjectManager $manager)
    {
        $accounts = [
            [
               'name' => 'Plan Vino',
               'type' => 1
            ],
            [
                'name' => 'Amauta',
                'type' => 1
             ]
        ];

        $this->build($accounts);
    }

    private function build($accounts)
    {
        foreach ($accounts as $data) {
            if (!$this->accountService->findOneBy(['name' => $data['name']])) {
                $this->accountService->create($data);
            }
        }
    }

    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
    }

}