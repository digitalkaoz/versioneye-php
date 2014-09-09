<?php

namespace Rs\VersionEye\Api;

use GuzzleHttp\Client;

/**
 * Products API
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 * @see https://www.versioneye.com/api/v2/swagger_doc/products
 */
class Products implements Api
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * search packages
     *
     * @param  string $query
     * @param  string $language
     * @param  string $group
     * @param  int    $page
     * @return array
     */
    public function search($query, $language = null, $group = null, $page = null)
    {
        return $this->client->get(sprintf('products/search/%s?%s', $query, http_build_query(array(
            'lang' => $language,
            'g' => $group,
            'page' => $page
        ))))->json()
        ;
    }
}
