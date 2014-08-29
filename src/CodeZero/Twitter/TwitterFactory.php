<?php namespace CodeZero\Twitter; 

use CodeZero\Twitter\Entities\Tweet;
use CodeZero\Twitter\Entities\User;

class TwitterFactory {

    /**
     * Create a Tweet
     *
     * @param array $tweet
     * @param User $user
     *
     * @return Tweet
     */
    public function createTweet(array $tweet, User $user)
    {
        return new Tweet($tweet, $user);
    }

    /**
     * Create a User
     *
     * @param array $user
     *
     * @return User
     */
    public function createUser(array $user)
    {
        return new User($user);
    }

}