<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class GetLinkTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * проверка получения оригинальной ссылки через api
     */
    public function testGetLink()
    {
        $testLink = $this->generateUrl();

        $responseShortLink = $this->json('POST', '/short_link', ['link' => $testLink])
            ->seeStatusCode(200);
        $resultLink = $responseShortLink->response->original['link'];

        $responseGetLink = $this->json('POST', '/get_link', ['link' => $resultLink])
            ->seeStatusCode(200)
            ->seeJson([
                'link' => $testLink,
            ]);
    }

    /**
     * проверка валидации (required) получения ссылки через api
     */
    public function testGetLinkValidateRequired()
    {
        $this->json('POST', '/get_link')
            ->seeJson([
                'link' => ['validation.required'],
            ])
            ->seeStatusCode(400);
    }

    /**
     * проверка валидации (корректности) получения ссылки через api
     */
    public function testGetLinkValidateCorrectness()
    {
        $this->json('POST', '/get_link', ['link' => str_random(10)])
            ->seeJson([
                'link' => ['validation.correctness'],
            ])
            ->seeStatusCode(400);
    }

    /**
     * проверка валидации (base64) получения ссылки через api
     */
    public function testGetLinkValidateBase64()
    {
        $this->json('POST', '/get_link', ['link' => config('app.site_url').'MQ123'])
            ->seeJson([
                'link' => ['validation.base64key'],
            ])
            ->seeStatusCode(400);
    }

    /**
     * проверка отсутствия ссылки через api
     */
    public function testGetLinknotFound()
    {
        $this->json('POST', '/get_link', ['link' => config('app.site_url').base64_encode(random_bytes(10))])
            ->seeJson([
                'link' => ['validation.not_found'],
            ])
            ->seeStatusCode(404);
    }

}
