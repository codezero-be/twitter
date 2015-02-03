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

class TestTwitterBaseSpec extends ObjectBehavior {

    private static $CONFIG = ['set1' => 'val1', 'set2' => 'val2'];

    function let(Configuration $configuration, Configurator $configurator, TwitterCourier $courier, AuthHelper $authHelper, UrlHelper $urlHelper, TwitterFactory $twitterFactory)
    {
        $this->beConstructedWith(SELF::$CONFIG, $configurator, $courier, $authHelper, $urlHelper, $twitterFactory);

        $configurator->load(SELF::$CONFIG)->willReturn($configuration);
        $configuration->get(Argument::type('string'))->willReturn('config');;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('CodeZero\Twitter\TwitterBase');
    }

    function it_is_initializable_with_constructor_config_argument_only()
    {
        $this->beConstructedWith(SELF::$CONFIG);
        $this->shouldHaveType('CodeZero\Twitter\TwitterBase');
    }

    function it_requests_a_twitter_app_access_token(Response $response, TwitterCourier $courier, AuthHelper $authHelper, UrlHelper $urlHelper)
    {
        $expectedEndpoint = '/oauth2/token';

        $data = ['grant_type' => 'client_credentials'];

        $headers = [
            'Authorization' => 'Basic creds',
            'Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'
        ];

        $authHelper->generateAppCredentials('config', 'config')->shouldBeCalled()->willReturn('creds');
        $urlHelper->joinSlugs(['config', $expectedEndpoint])->shouldBeCalled()->willReturn('url');
        $courier->post('url', $data, $headers)->shouldBeCalled()->willReturn($response);
        $response->toArray()->shouldBeCalled()->willReturn(['access_token' => 'accessToken']);
        $this->testRequestAppAccessToken()->shouldReturn('accessToken');
    }

    function it_verifies_that_the_count_parameter_is_in_range()
    {
        $this->testGetVerifiedCount(5, 1, 100)->shouldReturn(5);
        $this->testGetVerifiedCount(5, 6, 100)->shouldReturn(6);
        $this->testGetVerifiedCount(5, 1, 4)->shouldReturn(4);
    }

    function it_uses_the_smallest_value_to_compare_when_min_max_conflict()
    {
        $this->testGetVerifiedCount(5, 40, 4)->shouldReturn(4);
    }

    function it_creates_tweet_entities(TwitterFactory $twitterFactory, User $user)
    {
        $tweetsArray = [ 0 => ['user' => ['user'], 'text' => 'my tweet'] ];
        $firstTweet = $tweetsArray[0];
        $firstUserArray = $tweetsArray[0]['user'];
        $entity = 'entity'; //=> Var just for readability
        $arrayOfEntities = ['entity']; //=> Var just for readability

        // Create entities in foreach loop (1 cycle for test)
        $twitterFactory->createUser($firstUserArray)->shouldBeCalled()->willReturn($user);
        $twitterFactory->createTweet($firstTweet, $user)->shouldBeCalled()->willReturn($entity);

        $this->testCreateTweetEntities($tweetsArray)->shouldReturn($arrayOfEntities);
    }

}

namespace CodeZero\Twitter;

class TestTwitterBase extends TwitterBase {

    public function testRequestAppAccessToken()
    {
        return $this->requestAppAccessToken();
    }

    public function testGetVerifiedCount($count, $min = 1, $max = 100)
    {
        return $this->getVerifiedCount($count, $min, $max);
    }

    public function testCreateTweetEntities($tweets)
    {
        return $this->createTweetEntities($tweets);
    }

}