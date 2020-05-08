<?php

namespace App\DataFixtures;

use App\Services\CountryService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class  CountryFixtures extends Fixture implements ContainerAwareInterface
{
    private $container;
    private $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    public function load(ObjectManager $manager)
    {
        $countries = [
            [
               'name' => 'Argentina'
            ],
            [
               'name' => 'Uruguay'
            ],
            [
                'name' => 'Chile'
            ]
        ];

        $this->buildCountries($countries);
    }

    private function buildCountries($buildCountries)
    {
        foreach ($buildCountries as $data) {
            if (!$this->countryService->findOneBy(['name' => $data['name']])) {
                $this->countryService->create([
                    'name' => $data['name']
                ]);
            }
        }
    }

    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
    }

}