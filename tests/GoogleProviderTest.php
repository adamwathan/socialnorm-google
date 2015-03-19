<?php

use Mockery as M;
use SocialNorm\Google\GoogleProvider;
use SocialNorm\Request;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Subscriber\Mock as SubscriberMock;

class GoogleProviderTest extends TestCase
{
    private function getStubbedHttpClient($responses = [])
    {
        $client = new HttpClient;
        $mockSubscriber = new SubscriberMock($responses);
        $client->getEmitter()->attach($mockSubscriber);
        return $client;
    }

    /** @test */
    public function it_can_retrieve_a_normalized_user()
    {
        $client = $this->getStubbedHttpClient([
            __DIR__ . '/_fixtures/google_accesstoken.txt',
            __DIR__ . '/_fixtures/google_user.txt',
        ]);

        $provider = new GoogleProvider([
            'client_id' => 'abcdefgh',
            'client_secret' => '12345678',
            'redirect_uri' => 'http://example.com/login',
        ], $client, new Request(['code' => 'abc123']));

        $user = $provider->getUser();

        $this->assertEquals('103904294571447333816', $user->id);
        $this->assertEquals('adam.wathan@example.com', $user->nickname);
        $this->assertEquals('Adam Wathan', $user->full_name);
        $this->assertEquals('adam.wathan@example.com', $user->email);
        $this->assertEquals('https://lh3.googleusercontent.com/-w0_RpDnsIE4/AAAAAAAAAAI/AAAAAAAAAKM/NEiV3jig1HA/photo.jpg', $user->avatar);
        $this->assertEquals('ya29.8xFOTYpQK48RgPH8KjQpSu9SrcANcOQx9JtRnEu52dNsXqai8VD4iY3nFzUBURWnAPeTPtPeIBNjIF', $user->access_token);
    }

    /**
     * @test
     * @expectedException SocialNorm\Exceptions\ApplicationRejectedException
     */
    public function it_fails_to_retrieve_a_user_when_the_authorization_code_is_omitted()
    {
        $client = $this->getStubbedHttpClient([
            __DIR__ . '/_fixtures/google_accesstoken.txt',
            __DIR__ . '/_fixtures/google_user.txt',
        ]);

        $provider = new GoogleProvider([
            'client_id' => 'abcdefgh',
            'client_secret' => '12345678',
            'redirect_uri' => 'http://example.com/login',
        ], $client, new Request([]));

        $user = $provider->getUser();
    }
}
