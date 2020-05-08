<?php

namespace App\DataFixtures;

use App\Services\CompanyService;
use App\Services\CountryService;
use App\Services\RegionService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class  CompanyFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
{
    private $container;
    private $companyService;
    private $countryService;
    private $regionService;

    public function __construct(CompanyService $companyService, RegionService $regionService, CountryService $countryService)
    {
        $this->companyService = $companyService;
        $this->countryService = $countryService;
        $this->regionService = $regionService;
    }

    public function getDependencies()
    {
        return [
            CountryFixtures::class,
            RegionFixtures::class
        ];
    }


    public function load(ObjectManager $manager)
    {
        $country = $this->countryService->findOneBy(['name' => 'Argentina']);
        $region = $this->regionService->findOneBy(['name' => 'Colalao del Valle, Tucuman']);
        $companies = [
            [
               'name' => 'Luna de Cuarzo',
               'countries' => [
                $country
               ],
               'regions' => [
                $region
               ]
            ]
        ];

        $this->build($companies);
    }

    private function build($companies)
    {
        /*foreach ($companies as $data) {
            if (!$this->companyService->findOneBy(['name' => $data['name']])) {
                $this->companyService->create($data);
            }
        }*/
    }

    public function setContainer(ContainerInterface $container = null): void
    {
        $this->container = $container;
    }

}