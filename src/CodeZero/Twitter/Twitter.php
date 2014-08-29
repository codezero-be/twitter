<?php namespace CodeZero\Twitter;

use CodeZero\Courier\Response;

class Twitter extends TwitterBase {

    /**
     * Get tweets from a specific user
     *
     * @link https://dev.twitter.com/docs/api/1.1/get/statuses/user_timeline
     *
     * @param string $username
     * @param int $count
     * @param int $cacheMinutes
     * @param bool $returnEntities
     *
     * @throws TwitterException
     * @return Response
     */
    public function getTweetsFromUser($username, $count = 10, $cacheMinutes = 30, $returnEntities = true)
    {
        $count = $this->getVerifiedCount($count, 1, 200);

        $endpoint = '/statuses/user_timeline.json';

        $url = $this->urlHelper->joinSlugs([$this->baseUrl, $this->apiVersion, $endpoint]);

        $data = [
            'screen_name' => $username,
            'count' => $count
        ];

        $headers = [
            'Authorization' => 'Bearer ' . $this->requestAppAccessToken()
        ];

        $response = $this->courier->get($url, $data, $headers, $cacheMinutes);

        if ($returnEntities)
        {
            $tweets = $response->toArray();
            $response = $this->createTweetEntities($tweets);
        }

        return $response;
    }

    /**
     * Search tweets
     *
     * @link https://dev.twitter.com/docs/api/1.1/get/search/tweets
     *
     * @param string $query
     * @param int $count
     * @param int $cacheMinutes
     * @param bool $returnEntities
     *
     * @throws TwitterException
     * @return Response
     */
    public function searchTweets($query, $count = 10, $cacheMinutes = 30, $returnEntities = true)
    {
        $count = $this->getVerifiedCount($count, 1, 100);

        $endpoint = '/search/tweets.json';

        $url = $this->urlHelper->joinSlugs([$this->baseUrl, $this->apiVersion, $endpoint]);

        $data = [
            'q' => $query,
            'count' => $count
        ];

        $headers = [
            'Authorization' => 'Bearer ' . $this->requestAppAccessToken()
        ];

        $response = $this->courier->get($url, $data, $headers, $cacheMinutes);

        if ($returnEntities)
        {
            $tweets = $response->toArray();

            $response = $this->createTweetEntities($tweets['statuses']);
        }

        return $response;
    }

}