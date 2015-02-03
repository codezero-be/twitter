<?php namespace spec\CodeZero\Twitter;

use CodeZero\Courier\Courier;
use CodeZero\Courier\Response;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TwitterCourierSpec extends ObjectBehavior {

    function let(Courier $courier)
    {
        $this->beConstructedWith($courier);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('CodeZero\Twitter\TwitterCourier');
    }

    function it_sends_a_get_request(Courier $courier)
    {
        $courier->get('url', [], [], 30)->shouldBeCalled();
        $this->get('url', [], [], 30);
    }

    function it_sends_a_post_request(Courier $courier)
    {
        $courier->post('url', [], [], 30)->shouldBeCalled();
        $this->post('url', [], [], 30);
    }

    function it_sends_a_put_request(Courier $courier)
    {
        $courier->put('url', [], [], 0)->shouldBeCalled();
        $this->put('url', [], []);
    }

    function it_sends_a_patch_request(Courier $courier)
    {
        $courier->patch('url', [], [], 0)->shouldBeCalled();
        $this->patch('url', [], []);
    }

    function it_sends_a_delete_request(Courier $courier)
    {
        $courier->delete('url', [], [], 0)->shouldBeCalled();
        $this->delete('url', [], []);
    }

    function it_throws_on_http_request_errors(Courier $courier, Response $response)
    {
        $courier->get('url', [], [], 30)->shouldBeCalled()->willThrow('CodeZero\Courier\Exceptions\HttpRequestException');
        $response->getResponseType()->willReturn('some/type');
        $this->shouldThrow('CodeZero\Twitter\TwitterException')->duringGet('url', [], [], 30);
    }

    function it_rethrows_on_other_request_errors(Courier $courier)
    {
        $courier->get('url', [], [], 30)->shouldBeCalled()->willThrow('CodeZero\Courier\Exceptions\RequestException');
        $this->shouldThrow('CodeZero\Courier\Exceptions\RequestException')->duringGet('url', [], [], 30);
    }

}

namespace CodeZero\Courier\Exceptions;

use Exception;

class HttpRequestException extends Exception {

    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function response()
    {
        return new Response();
    }

}

class Response {

    public function getResponseType()
    {
        return 'some/type';
    }

}