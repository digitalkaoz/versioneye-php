<?php

namespace Rs\VersionEye\Http;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Plugin\AuthenticationPlugin;
use Http\Client\Plugin\DecoderPlugin;
use Http\Client\Plugin\ErrorPlugin;
use Http\Client\Plugin\PluginClient;
use Http\Client\Plugin\RedirectPlugin;
use Http\Client\Plugin\RetryPlugin;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\MessageFactoryDiscovery;
use Http\Message\Authentication\QueryParam;

/**
 * Factory for creating Http Client.
 *
 * @author Robert Schönthal <robert.schoenthal@gmail.com>
 */
class ClientFactory
{
    /**
     * @param string $url
     * @param string $token
     *
     * @return HttpClient
     */
    public static function create($url, $token)
    {
        $plugins = [
            new RedirectPlugin(),
            new RetryPlugin(['retries' => 5]),
            new DecoderPlugin(),
            new ErrorPlugin(),
        ];

        if ($token) {
            $plugins[] = new AuthenticationPlugin(new QueryParam([
                'api_key' => $token,
            ]));
        }

        $client = new HttpMethodsClient(
            new PluginClient(HttpClientDiscovery::find(), $plugins),
            MessageFactoryDiscovery::find()
        );

        return new HttpPlugHttpAdapterClient($client, $url);
    }
}
