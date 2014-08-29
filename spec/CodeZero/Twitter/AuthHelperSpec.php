<?php namespace spec\CodeZero\Twitter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AuthHelperSpec extends ObjectBehavior {

    function it_is_initializable()
    {
        $this->shouldHaveType('CodeZero\Twitter\AuthHelper');
    }

    function it_generates_credentials_from_an_api_key_and_api_secret()
    {
        $this->generateAppCredentials('apiKey', 'apiSecret')
            ->shouldReturn('YXBpS2V5OmFwaVNlY3JldA==');
    }

}