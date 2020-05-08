<?php

namespace App\DataFixtures;

use App\Services\GrapeService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class  GrapeFixtures extends Fixture implements ContainerAwareInterface
{
    private $container;
    private $grapeService;

    public function __construct(GrapeService $grapeService)
    {
        $this->grapeService = $grapeService;
    }

    public function load(ObjectManager $manager)
    {
        $grapes = [
            [
               'name' => 'Malbec'
            ],
            [
               'name' => 'Cabernet Sauvignon'
            ],
            [
                'name' => 'Cabernet Franc'
            ],
            [
                'name' => 'Bonarda'
            ]
        ];

        $this->build($grapes);
    }

    private function build($grapes)
    {
        foreach ($grapes as $data) {
            if (!$this->grapeService->findOneBy(['name' => $data['name']])) {
                $this->grapeService->create($data);
            }
        }
    }

    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
    }

}