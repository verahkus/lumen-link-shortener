<?php

use App\Model\Link;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ShortLinkTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * проверка создания ссылки
     */
    public function testCreateLinkFactory()
    {
        $links = factory(Link::class, 10)->create();
        $this->updateKeyLinks();
        $this->assertEquals(10, $links->count());
    }

    /**
     * проверка сокращения уже созданной ссылки
     */
    public function testShortLinkFactory()
    {
        $testLink = $this->generateUrl();
        $link = factory(Link::class)->create([
            'url' => $testLink,
        ]);
        $this->updateKeyLinks();
        $response = $this->json('POST', '/short_link', ['link' => $testLink])
            ->seeJson([
                'link' => config('app.site_url').Link::find($link->id)->key,
            ])
            ->seeStatusCode(200);
    }

    /**
     * проверка создания новой ссылки через api
     */
    public function testShortLinkJson()
    {
        $testLink = $this->generateUrl();
        $this->json('POST', '/short_link', ['link' => $testLink])->seeStatusCode(200);
        $this->seeInDatabase('links', ['url' => $testLink]);
    }

    /**
     * проверка валидации (required) новой ссылки через api
     */
    public function testShortLinkJsonValidateRequiredLink()
    {
        $this->json('POST', '/short_link')
            ->seeJson([
                'link' => ['validation.required'],
            ])
            ->seeStatusCode(400);
    }

    /**
     * проверка валидации (корректности) новой ссылки через api
     */
    public function testShortLinkJsonValidateCorrectnessLink()
    {
        $this->json('POST', '/short_link', ['link' => str_random(10)])
            ->seeJson([
                'link' => ['validation.correctness'],
            ])
            ->seeStatusCode(400);
    }
}
