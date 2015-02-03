<?php namespace CodeZero\Twitter\Entities;

class Tweet {

    /**
     * Tweet properties
     *
     * @var array
     */
    private $fields;

    /**
     * Constructor
     *
     * @param array $tweet
     * @param User $user
     */
    public function __construct(array $tweet, User $user)
    {
        $this->createTweet($tweet, $user);
    }

    /**
     * Print the tweet
     *
     * @return string
     */
    public function  __toString()
    {
        return $this->getText();
    }

    /**
     * Get the ID of the tweet
     *
     * @return int
     */
    public function getId()
    {
        return $this->fields['id'];
    }

    /**
     * Get the text of the tweet
     *
     * @return string
     */
    public function getText()
    {
        return $this->fields['text'];
    }

    /**
     * Get the source of the tweet
     *
     * @return string
     */
    public function getSource()
    {
        return $this->fields['source'];
    }

    /**
     * Get URL of the tweet
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->fields['url'];
    }

    /**
     * Get the total number of retweets of the tweet
     *
     * @return int
     */
    public function getRetweetCount()
    {
        return $this->fields['retweet_count'];
    }

    /**
     * Get total number of times the tweet was favorited
     *
     * @return int
     */
    public function getFavoriteCount()
    {
        return $this->fields['favorite_count'];
    }

    /**
     * Get the date the tweet was created
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->fields['created_at'];
    }

    /**
     * Get hash tags of the tweet
     *
     * @return array
     */
    public function getHashTags()
    {
        return $this->fields['hashtags'];
    }

    /**
     * Get user of the tweet
     *
     * @return User
     */
    public function user()
    {
        return $this->fields['user'];
    }

    /**
     * Set most used tweet properties
     *
     * @link https://dev.twitter.com/docs/platform-objects/tweets
     *
     * @param array $tweet
     * @param User $user
     *
     * @return void
     */
    private function createTweet(array $tweet, User $user)
    {
        $this->fields = [
            'id'             => $this->getValue('id', $tweet),
            'text'           => $this->getValue('text', $tweet),
            'source'         => $this->getValue('source', $tweet),
            'url'            => $this->getValue('url', $tweet),
            'retweet_count'  => $this->getValue('retweet_count', $tweet),
            'favorite_count' => $this->getValue('favorite_count', $tweet),
            'created_at'     => $this->getValue('created_at', $tweet),
            'hashtags'       => $this->listHashTags($tweet),
            'user'           => $user
        ];
    }

    /**
     * List all tweet hash tags
     *
     * @param array $tweet
     *
     * @return array
     */
    private function listHashTags(array $tweet)
    {
        $list = [];

        if (array_key_exists('entities', $tweet) and array_key_exists('hashtags', $tweet['entities']))
        {
            $hashTags = $tweet['entities']['hashtags'];

            foreach ($hashTags as $hashTag)
            {
                if (array_key_exists('text', $hashTag))
                {
                    $list[] = $hashTag['text'];
                }
            }
        }

        return $list;
    }

    /**
     * Get the array value if the specified key exists
     *
     * @param string $key
     * @param array $array
     *
     * @return string
     */
    private function getValue($key, array $array)
    {
        return array_key_exists($key, $array)
            ? $array[$key]
            : '';
    }

} 