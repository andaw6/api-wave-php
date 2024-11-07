<?php

namespace App\Services;

use App\Services\interface\GeneratorSenegalPhoneNumerServiceInterface;
use Faker\Factory as Faker;

class GeneratorSenegalPhoneNumerService implements GeneratorSenegalPhoneNumerServiceInterface
{
    private static $prefixes = ['77', '78', '70', '76', '75'];
    
    public function generate(): string {
        $faker = Faker::create();
       return $faker->numerify($faker->randomElement(self::$prefixes) . '#######');
    }
}
