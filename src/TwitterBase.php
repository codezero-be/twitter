<?php namespace CodeZero\Twitter;

use CodeZero\Configurator\Configurator;
use CodeZero\Configurator\DefaultConfigurator;
use CodeZero\Utilities\UrlHelper;

abstract class TwitterBase {

    /**
     * Twitter Courier
     *
     * @var TwitterCourier
     */
    protected $courier;

    /**
     * Twitter Auth
     *
     * @var AuthHelper
     */
    protected $authHelper;

    /**
     * URL Helper
     *
     * @var UrlHelper
     */
    protected $urlHelper;

    /**
     * Twitter Base URL
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * Twitter API Version
     *
     * @var string
     */
    protected $apiVersion;

    /**
     * Twitter App API Key
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Twitter App API Secret
     *
     * @var string
     */
    protected $apiSecret;

    /**
     * Twitter Entity Factory
     *
     * @var TwitterFactory
     */
    private $twitterFactory;

    /**
     * Constructor
     *
     * @param $config
     * @param Configurator $configurator
     * @param TwitterCourier $courier
     * @param AuthHelper $authHelper
     * @param UrlHelper $urlHelper
     * @param TwitterFactory $twitterFactory
     *
     * @throws \CodeZero\Configurator\ConfigurationException
     */
    public function __construct($config, Configurator $configurator = null, TwitterCourier $courier = null, AuthHelper $authHelper = null, UrlHelper $urlHelper = null, TwitterFactory $twitterFactory = null)
    {
        $this->courier = $courier ?: new TwitterCourier();
        $this->authHelper = $authHelper ?: new AuthHelper();
        $this->urlHelper = $urlHelper ?: new UrlHelper();
        $this->twitterFactory = $twitterFactory ?: new TwitterFactory();

        $configurator = $configurator ?: new DefaultConfigurator();
        $config = $configurator->load($config);

        $this->baseUrl = $config->get('base_url');
        $this->apiVersion = $config->get('api_version');
        $this->apiKey = $config->get('api_key');
        $this->apiSecret = $config->get('api_secret');
    }

    /**
     * Request an app access token
     *
     * @link https://dev.twitter.com/docs/api/1.1/post/oauth2/token
     *
     * @throws TwitterException
     * @return string
     */
    protected function requestAppAccessToken()
    {
        $credentials = $this->authHelper->generateAppCredentials($this->apiKey, $this->apiSecret);

        $endpoint = '/oauth2/token';

        $url = $this->urlHelper->joinSlugs([$this->baseUrl, $endpoint]);

        $data = ['grant_type' => 'client_credentials'];

        $headers = [
            'Authorization' => 'Basic ' . $credentials,
            'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
        ];

        $response = $this->courier->post($url, $data, $headers)->toArray();

        return $response['access_token'];
    }

    /**
     * Verify that the count parameter is in range
     *
     * @param int $count
     * @param int $min
     * @param int $max
     *
     * @return int
     */
    protected function getVerifiedCount($count, $min = 1, $max = 100)
    {
        settype($count, "integer");
        settype($min, "integer");
        settype($max, "integer");

        // If $min is greater than $max,
        // use the smallest of the two ($max)
        if ($min > $max) $min = $max;

        if ($count < $min)
        {
            $count = $min;
        }
        elseif ($count > $max)
        {
            $count = $max;
        }

        return $count;
    }

    /**
     * Create Tweet Entities
     *
     * @param $tweets
     *
     * @return array
     */
    protected function createTweetEntities($tweets)
    {
        $entities = [];

        foreach ($tweets as $tweet)
        {
            $user = $this->twitterFactory->createUser($tweet['user']);
            $entities[] = $this->twitterFactory->createTweet($tweet, $user);
        }

        return $entities;
    }

}