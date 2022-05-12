<?php

namespace App\DataFixtures;

use App\Entity\Stats;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StatsFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $stats = [
            [
                "nom" => "chien",
                "value" => 400
            ],
            [
                "nom" => "lapin",
                "value" => 326
            ],
            [
                "nom" => "oiseaux",
                "value" => 900
            ]
        ];

        foreach ($stats as $stat){
            $statToAdd = new Stats();
            $statToAdd->setName($stat['name']);
            $statToAdd->setValue($stat['value']);
            
            $manager->persist($statToAdd);
        }
        $manager->flush();
    }
}
