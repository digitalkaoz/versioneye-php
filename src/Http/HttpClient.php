<?php


namespace Rs\VersionEye\Http;

/**
 * HttpClient
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
interface HttpClient
{
    /**
     * performs a HTTP Request to the API Endpoint
     *
     * @param  string $method
     * @param  string $url
     * @param  array  $params
     * @return array
     * @throws CommunicationException
     */
    public function request($method, $url, array $params = []);
}
