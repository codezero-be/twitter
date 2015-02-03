# Simple Twitter API Wrapper #

[![Build Status](https://travis-ci.org/codezero-be/twitter.svg?branch=master)](https://travis-ci.org/codezero-be/twitter)
[![Latest Stable Version](https://poser.pugx.org/codezero/twitter/v/stable.svg)](https://packagist.org/packages/codezero/twitter)
[![Total Downloads](https://poser.pugx.org/codezero/twitter/downloads.svg)](https://packagist.org/packages/codezero/twitter)
[![License](https://poser.pugx.org/codezero/twitter/license.svg)](https://packagist.org/packages/codezero/twitter)

This package hides away the complexity of "talking" to the Twitter API, but instead offers a few simple functions to execute some basic queries.

## Features ##

- All queries require an **API key** and **API secret** to generate App Credentials
- Authorization will be triggered automatically behind the scenes
- Fetch a users tweets with a single method call
- Search Twitter for a hash tag or keyword
- Get the results in standard JSON format, or use our simplified `Tweet` objects
- Optional Caching (only [Laravel](http://www.laravel.com/ "Laravel") implementation included, see [codezero-be/courier](https://github.com/codezero-be/courier))
- Optional [Laravel](http://www.laravel.com/ "Laravel") ServiceProvider included

## Installation ##

Install this package through Composer:

    "require": {
    	"codezero/twitter": "1.*"
    }

## Setup ##

### Laravel 4 Setup ###

After installing, update your `app/config/app.php` file to include a reference to this package's service provider in the providers array:

    'providers' => [
	    'CodeZero\Twitter\TwitterServiceProvider'
    ]

Next, create the [config](#edit-configuration "Configuration File") file: `app/config/twitter.php`. Or publish the package config file by running this command in the console:

	php artisan config:publish codezero/twitter

### Manual Setup ###

If you don't use Laravel, you will have to instantiate all of the dependencies manually.

##### Create a Courier instance: #####

You'll need to inject an instance of `Courier`. Please refer to [codezero-be/courier](https://github.com/codezero-be/courier) for detailed information on how to instantiate this dependency.

	$courier = new Courier(...);

##### Locate configuration file: #####

Specify the location of your config file. An example configuration file is included in the `src/config` folder. You can put this anywhere you want.

    $config = '/path/to/configFile.php';

##### Instantiate dependencies: #####

	$loader = new \CodeZero\Configurator\Loader();
    $configurator = new \CodeZero\Configurator\Configurator($loader, $config);
    $twitterCourier = new \CodeZero\Twitter\TwitterCourier($courier);
    $authHelper = new \CodeZero\Twitter\AuthHelper();
    $urlHelper = new \CodeZero\Utilities\UrlHelper();
    $twitterFactory = new \CodeZero\Twitter\TwitterFactory();

##### Create Twitter instance: #####

    $twitter = new \CodeZero\Twitter\Twitter($configurator, $twitterCourier, $authHelper, $urlHelper, $twitterFactory);

## Edit Configuration ##

Your configuration file should look like the following:

	<?php
	return [
	    'base_url' => 'https://api.twitter.com/',
	    'api_version' => '1.1',
	    'api_key' => '',
	    'api_secret' => ''
	];

Be sure to enter your API key and API secret. Twitter requires this for all requests. Also, do not include the API version in the `base_url` as this would break the authorization request.

## Usage ##

### Set options ###

The number of results to return. Each Twitter request has a maximum limit. If you specify a `$count` greater than this limit, the maximum results will be returned. (Default: `10`)

	$count = 10;

The number of minutes the results of the query should be cached. Twitter sets a request limit per hour, so caching is a good idea. Setting this to `0` (zero) will disable caching. (Default: `30`)

	$cacheMinutes = 30;

This package includes a `Tweet` object which greatly simplifies the returned results. If you want the full JSON response to be returned, set this to `false`. (Default: `true`)

	$returnEntities = true;

### Get tweets ###

	try
	{
		$username = 'laravelphp'; //=> Example...
		$tweets = $twitter->getTweetsFromUser($username, $count, $cacheMinutes, $returnEntities);
	}
	catch (\CodeZero\Twitter\TwitterException $e)
	{
		$error = $e->getMessage(); //=> user not found etc.
	}
### Response formats ###

#### JSON ####

If you `$returnEntities` is `false`, you get a `CodeZero\Courier\Response` object, which contains the actual JSON response.

	$tweets = $twitter->getTweetsFromUser($username);

	echo $tweets; //=> Print the JSON
	$json = $tweets->getBody(); //=> Returns the JSON
	$array = $tweets->toArray(); //=> Convert JSON to an array

For more information on this `Response` object, refer to [codezero-be/courier](https://github.com/codezero-be/courier).

#### Tweets ####

If you `$returnEntities` is `true`, you get an array of `CodeZero\Twitter\Entities\Tweet` objects. This is a very simplified `Tweet` object, which only contains the most useful info about the tweet and its user.

	$tweets = $twitter->getTweetsFromUser($username);
	
	foreach ($tweets as $tweet)
	{
		$user = $tweet->user();
		$tweetOwner = $user->getName();
		$tweetUsername = $user->getUsername();
		$tweetText = $tweet->getText();
		$tweetDate = $tweet->getCreatedAt();
	}

For an overview of all available `Tweet` and `User` information, take a look at the source.

## Available Requests ##

### Get tweets from a user ###

	$username = 'laravelphp';
	$tweets = $twitter->getTweetsFromUser($username, $count, $cacheMinutes, $returnEntities);

### Search for tweets with a hash tag or keyword ###

	$query = '#laravel';
	$tweets = $twitter->searchTweets($query, $count, $cacheMinutes, $returnEntities);

### That's all for now... ###

---
[![Analytics](https://ga-beacon.appspot.com/UA-58876018-1/codezero-be/twitter)](https://github.com/igrigorik/ga-beacon)