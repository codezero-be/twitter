<?php namespace CodeZero\Twitter\Entities; 

class User {

    /**
     * User properties
     *
     * @var array
     */
    private $fields;

    /**
     * Constructor
     *
     * @param array $user
     */
    public function __construct(array $user)
    {
        $this->createUser($user);
    }

    /**
     * Print the user name
     *
     * @return string
     */
    public function  __toString()
    {
        return $this->getName();
    }

    /**
     * Get the ID of the user
     *
     * @return int
     */
    public function getId()
    {
        return $this->fields['id'];
    }

    /**
     * Get the name of the user
     *
     * @return string
     */
    public function getName()
    {
        return $this->fields['name'];
    }

    /**
     * Get the username of the user
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->fields['username'];
    }

    /**
     * Get the description of the user
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->fields['description'];
    }

    /**
     * Get the location of the user
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->fields['location'];
    }

    /**
     * Get the url of the user
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->fields['url'];
    }

    /**
     * Get the total number of followers of the user
     *
     * @return int
     */
    public function getFollowersCount()
    {
        return $this->fields['followers_count'];
    }

    /**
     * Get the total number of friends of the user
     *
     * @return int
     */
    public function getFriendsCount()
    {
        return $this->fields['friends_count'];
    }

    /**
     * Get the total number of favourites of the user
     *
     * @return int
     */
    public function getFavouritesCount()
    {
        return $this->fields['favourites_count'];
    }

    /**
     * Get the total number of lists the user is listed in
     *
     * @return int
     */
    public function getListedCount()
    {
        return $this->fields['listed_count'];
    }

    /**
     * Get the date the user was created at Twitter
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->fields['created_at'];
    }

    /**
     * Set most used user properties
     *
     * @link https://dev.twitter.com/docs/platform-objects/users
     *
     * @param array $user
     *
     * @return void
     */
    private function createUser(array $user)
    {
        $this->fields = [
            'id'               => $this->getValue('id', $user),
            'name'             => $this->getValue('name', $user),
            'username'         => $this->getValue('username', $user),
            'description'      => $this->getValue('description', $user),
            'location'         => $this->getValue('location', $user),
            'url'              => $this->getValue('url', $user),
            'followers_count'  => $this->getValue('followers_count', $user),
            'friends_count'    => $this->getValue('friends_count', $user),
            'favourites_count' => $this->getValue('favourites_count', $user),
            'listed_count'     => $this->getValue('listed_count', $user),
            'created_at'       => $this->getValue('created_at', $user)
        ];
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