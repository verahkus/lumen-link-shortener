<?php

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    /**
     * генерация случайного url
     * @return string
     */
    public function generateUrl()
    {
        $faker = Faker\Factory::create();
        return $faker->randomElement($array = array ('http://','https://')).$faker->domainName.'/'.str_random(10);
    }
}
