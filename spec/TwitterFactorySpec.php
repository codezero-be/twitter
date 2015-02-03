<?php namespace spec\CodeZero\Twitter;

use CodeZero\Twitter\Entities\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TwitterFactorySpec extends ObjectBehavior {

    function it_is_initializable()
    {
        $this->shouldHaveType('CodeZero\Twitter\TwitterFactory');
    }

    function it_creates_a_user()
    {
        $this->createUser([])->shouldReturnAnInstanceOf('CodeZero\Twitter\Entities\User');
    }

    function it_creates_a_tweet(User $user)
    {
        $this->createTweet([], $user)->shouldReturnAnInstanceOf('CodeZero\Twitter\Entities\Tweet');
    }

}