<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    public function testSomething()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(0, $crawler->filter('html:contains("Hello World")')->count());
    }

    public function testCheckPassword()
    {
        $client = static::createClient();

        $crawler = $client->request(
            'GET',
            '/register'
        );

        $form = $crawler->selectButton('S\'inscrire')->form();

        $form['user[email]'] = 'rhahlasaif91@email.com';
        $form['user[username]'] = 'seif';
        $form['user[fullName]'] = 'seif rhahla';
        $form['user[password][first]'] = '123456';
        $form['user[password][second]'] = '123456';

        $crawler = $client->submit($form);

        //echo $client->getResponse()->getContent();


        $this->assertEquals(1,
            $crawler->filter('li:contains("This value is not valid.")')->count()
        );
    }
}
