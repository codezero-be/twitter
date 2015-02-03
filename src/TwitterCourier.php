<?php namespace CodeZero\Twitter; 

use CodeZero\Courier\Courier;
use CodeZero\Courier\CurlCourier;
use CodeZero\Courier\Exceptions\HttpRequestException;
use CodeZero\Courier\Response;

class TwitterCourier {

    /**
     * Courier
     *
     * @var Courier
     */
    private $courier;

    /**
     * Constructor
     *
     * @param Courier $courier
     */
    public function __construct(Courier $courier = null)
    {
        $this->courier = $courier ?: new CurlCourier();
    }

    /**
     * Send GET request
     *
     * @param string $url
     * @param array $data
     * @param array $headers
     * @param int $cacheMinutes
     *
     * @throws TwitterException
     * @return Response
     */
    public function get($url, array $data = [], array $headers = [], $cacheMinutes = 30)
    {
        return $this->send('get', $url, $data, $headers, $cacheMinutes);
    }

    /**
     * Send POST request
     *
     * @param string $url
     * @param array $data
     * @param array $headers
     * @param int $cacheMinutes
     *
     * @throws TwitterException
     * @return Response
     */
    public function post($url, array $data = [], array $headers = [], $cacheMinutes = 30)
    {
        return $this->send('post', $url, $data, $headers, $cacheMinutes);
    }

    /**
     * Send PUT request
     *
     * @param string $url
     * @param array $data
     * @param array $headers
     *
     * @throws TwitterException
     * @return Response
     */
    public function put($url, array $data = [], array $headers = [])
    {
        return $this->send('put', $url, $data, $headers);
    }

    /**
     * Send PATCH request
     *
     * @param string $url
     * @param array $data
     * @param array $headers
     *
     * @throws TwitterException
     * @return Response
     */
    public function patch($url, array $data = [], array $headers = [])
    {
        return $this->send('patch', $url, $data, $headers);
    }

    /**
     * Send DELETE request
     *
     * @param string $url
     * @param array $data
     * @param array $headers
     *
     * @throws TwitterException
     * @return Response
     */
    public function delete($url, array $data = [], array $headers = [])
    {
        return $this->send('delete', $url, $data, $headers);
    }

    /**
     * Send a request
     *
     * @param string $method
     * @param string $url
     * @param array $data
     * @param array $headers
     * @param int $cacheMinutes
     *
     * @throws TwitterException
     * @return Response
     */
    private function send($method, $url, array $data = [], array $headers = [], $cacheMinutes = 0)
    {
        $method = strtolower($method);

        try
        {
            return $this->courier->$method($url, $data, $headers, $cacheMinutes);
        }
        catch (HttpRequestException $exception)
        {
            $response = $exception->response();

            if ($response->getResponseType() == 'application/json')
            {
                $response = $response->toArray();
                $error = sprintf('Twitter Error: %s (Error %d)', $response['errors'][0]['message'], $response['errors'][0]['code']);
            }
            else
            {
                $error = sprintf('HTTP Error: %s (Error %d)', $exception->getMessage(), $exception->getCode());
            }

            throw new TwitterException($error);
        }
    }

}