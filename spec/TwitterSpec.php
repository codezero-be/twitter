<?php namespace spec\CodeZero\Twitter;

use CodeZero\Configurator\Configuration;
use CodeZero\Configurator\Configurator;
use CodeZero\Courier\Response;
use CodeZero\Twitter\AuthHelper;
use CodeZero\Twitter\Entities\User;
use CodeZero\Twitter\TwitterCourier;
use CodeZero\Twitter\TwitterFactory;
use CodeZero\Utilities\UrlHelper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TwitterSpec extends ObjectBehavior {

    private static $CONFIG = ['set1' => 'val1', 'set2' => 'val2'];

    function let(Configuration $configuration, Response $response, Configurator $configurator, TwitterCourier $courier, AuthHelper $authHelper, UrlHelper $urlHelper, TwitterFactory $twitterFactory)
    {
        $this->beConstructedWith(self::$CONFIG, $configurator, $courier, $authHelper, $urlHelper, $twitterFactory);

        $configurator->load(self::$CONFIG)->willReturn($configuration);
        $configuration->get(Argument::type('string'))->willReturn('config');;

        // Request Access Token
        $authHelper->generateAppCredentials('config', 'config')->willReturn();
        $urlHelper->joinSlugs(Argument::type('array'))->willReturn('url');
        $courier->post('url', Argument::type('array'), Argument::type('array'))->willReturn($response);
        $response->toArray()->willReturn(['access_token' => 'accessToken']);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('CodeZero\Twitter\Twitter');
    }

    function it_is_initializable_with_constructor_config_argument_only()
    {
        $this->beConstructedWith(self::$CONFIG);
        $this->shouldHaveType('CodeZero\Twitter\Twitter');
    }

    function it_gets_tweets_from_a_user(TwitterCourier $courier, UrlHelper $urlHelper)
    {
        $expectedEndpoint = '/statuses/user_timeline.json';
        $data = ['screen_name' => 'username', 'count' => 10];
        $headers = ['Authorization' => 'Bearer accessToken'];
        $returnEntities = false;
        $urlHelper->joinSlugs(['config', 'config', $expectedEndpoint])->shouldBeCalled()->willReturn('url');
        $courier->get('url', $data, $headers, 30)->shouldBeCalled()->willReturn('response');
        $this->getTweetsFromUser('username', 10, 30, $returnEntities)->shouldReturn('response');
    }

    function it_searches_tweets(TwitterCourier $courier, UrlHelper $urlHelper)
    {
        $expectedEndpoint = '/search/tweets.json';
        $data = ['q' => '#twitter', 'count' => 10];
        $headers = ['Authorization' => 'Bearer accessToken'];
        $returnEntities = false;
        $urlHelper->joinSlugs(['config', 'config', $expectedEndpoint])->shouldBeCalled()->willReturn('url');
        $courier->get('url', $data, $headers, 30)->shouldBeCalled()->willReturn('response');
        $this->searchTweets('#twitter', 10, 30, $returnEntities)->shouldReturn('response');
    }

    function it_returns_results_as_tweet_entities(TwitterCourier $courier, UrlHelper $urlHelper, TwitterFactory $twitterFactory, User $user, Response $response2)
    {
        $expectedEndpoint = '/statuses/user_timeline.json';
        $data = ['screen_name' => 'username', 'count' => 10];
        $headers = ['Authorization' => 'Bearer accessToken'];
        $returnEntities = true;
        $urlHelper->joinSlugs(['config', 'config', $expectedEndpoint])->shouldBeCalled()->willReturn('url');
        $courier->get('url', $data, $headers, 30)->shouldBeCalled()->willReturn($response2);

        $tweetsArray = [ 0 => ['user' => ['user'], 'text' => 'my tweet'] ];
        $firstTweet = $tweetsArray[0];
        $firstUserArray = $firstTweet['user'];
        $entity = 'entity'; //=> Var just for readability
        $arrayOfEntities = ['entity']; //=> Var just for readability

        $response2->toArray()->willReturn($tweetsArray);

        // Create entities in foreach loop (1 cycle for test)
        $twitterFactory->createUser($firstUserArray)->shouldBeCalled()->willReturn($user);
        $twitterFactory->createTweet($firstTweet, $user)->shouldBeCalled()->willReturn($entity);

        // Resulting array of entities should be returned
        $this->getTweetsFromUser('username', 10, 30, $returnEntities)->shouldReturn($arrayOfEntities);
    }

}