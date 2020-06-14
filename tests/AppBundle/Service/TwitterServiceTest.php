<?php

namespace Tests\AppBundle\Service;

use AppBundle\Service\TwitterService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TwitterServiceTest extends WebTestCase{


    /**
     * test the api token are correct
     */
    public function testOauth(){
        $client = static::createClient();
        $container = $client->getContainer();
        $twitterService = $container->get(TwitterService::class);
        $json = $twitterService->searchTwitte('test','#');
        $this->assertEquals(true, isset($json->statuses));
    }

    /**
     * test all the searched #value include #value
     */
    public function testSearchHashtag(){
        $client = static::createClient();
        $container = $client->getContainer();
        $twitterService = $container->get(TwitterService::class);
        $twittes = $twitterService->twitteResponse('test','#');
        foreach($twittes as $twitte){
            $text = $twitte->getText();
            preg_match_all("/(#test)/i", $text, $matches);
            $include_hashtag = count($matches[0]) !== 0;
            if(!$include_hashtag && $twitte->getReText()){
                $retwitte_text = $twitte->getReText();
                preg_match_all("/(#test)/i", $retwitte_text, $matches);
                $include_hashtag = count($matches[0]) !== 0;   
            }
            
            $this->assertEquals(true, $include_hashtag);
        }
    }


    /**
     * test all the search @user include @user
     */
    public function testMentionUser(){
        $client = static::createClient();
        $container = $client->getContainer();
        $twitterService = $container->get(TwitterService::class);
        $twittes = $twitterService->twitteResponse('Sydney','@');

        foreach($twittes as $twitte){
            $text = $twitte->getText();
            preg_match_all("/(@Sydney)/i", $text, $matches);
            $include_hashtag = count($matches[0]) !== 0;
            if(!$include_hashtag && $twitte->getReText()){
                $retwitte_text = $twitte->getReText();
                preg_match_all("/(@Sydney)/i", $retwitte_text, $matches);
                $include_hashtag = count($matches[0]) !== 0;   
            }
            
            $this->assertEquals(true, $include_hashtag);
        }
    }

}