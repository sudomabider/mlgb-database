<?php


namespace App\Providers\ClashOfClans;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;

class Client
{
    protected $httpClient;

    protected $token, $tag;

    public function __construct($token, $tag)
    {
        $this->token = $token;
        $this->tag = $tag;
    }

    /**
     * Get full details for specific clan
     *
     * @return \Illuminate\Support\Collection
     */
    public function getClan()
    {
        $response = $this->request('clans/' . urlencode($this->tag));

        return arrayToCollection($response);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getMembers()
    {
        $response = $this->request('clans/' . urlencode($this->tag) . '/members');

        return arrayToCollection($response['items']);
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function getLeagues()
    {
        $response = $this->request('leagues');

        return arrayToCollection($response['items']);
    }
    /**
     * Get full warlog
     *
     * @param array $options
     * @return \Illuminate\Support\Collection
     */
    public function getWarLog(array $options = [])
    {
        $response = $this->request('clans/' . urlencode($this->tag) . '/warlog', $options);

        return arrayToCollection($response);
    }

    /**
     * @param $url
     * @param array $options
     * @return array
     */
    protected function request($url, $options = [])
    {
        $response = $this->getHttpClient()
                         ->request('GET', $url, ['headers' => ['Authorization' => 'Bearer ' . $this->getToken()]] + $options);

        return $this->convertResponseToArray($response);
    }

    /**
     * @param Response $response
     * @return array
     */
    private function convertResponseToArray(Response $response)
    {
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @return GuzzleClient
     */
    public function getHttpClient()
    {
        if($this->httpClient === null)
        {
            $this->httpClient = new GuzzleClient(['base_uri' => 'https://api.clashofclans.com/v1/']);
        }

        return $this->httpClient;
    }

    public function setHttpClient(ClientInterface $client)
    {
        $this->httpClient = $client;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getToken()
    {
        return $this->token;
    }

}
