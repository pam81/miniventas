<?php

namespace App\DataFixtures;

use App\Services\RegionService;
use App\Services\CountryService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class  RegionFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
{
    private $container;
    private $regionService;
    private $countryService;

    public function __construct(RegionService $regionService, CountryService $countryService)
    {
        $this->regionService = $regionService;
        $this->countryService = $countryService;
    }

    public function getDependencies()
    {
        return [
            CountryFixtures::class
        ];
    }

    public function load(ObjectManager $manager)
    {
        $country = $this->countryService->findOneBy(['name' => 'Argentina']);

        $regions = [
            [
               'name' => 'Jujuy',
               'country' => $country
            ],
            [
               'name' => 'Cafayate, Salta',
               'country' => $country
            ],
            [
                'name' => 'Colalao del Valle, Tucuman',
                'country' => $country
            ],
            [
                'name' => 'Mendoza',
                'country' => $country
            ]
        ];

        $this->buildCountries($regions);
    }

    private function buildCountries($regions)
    {
        foreach ($regions as $data) {
            if (!$this->regionService->findOneBy(['name' => $data['name']])) {
                $this->regionService->create($data);
            }
        }
    }

    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
    }

}