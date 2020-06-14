<?php

namespace AppBundle\Service;

use AppBundle\Entity\Twitte;

/**
 * Twitter Service, search twittes via twitter search API
 *
 * Class ReasonService
 * @package AppBundle\Service
 */
class TwitterService
{

  private $consumerKey;
  private $consumerSecret;
  private $baseUrl;
  private $accessToken;
  private $accessSecret;


  public function __construct($consumerKey, $consumerSecret, $baseUrl, $accessToken, $accessSecret)
  {
    $this->consumerKey = $consumerKey;
    $this->consumerSecret = $consumerSecret;
    $this->baseUrl = $baseUrl;
    $this->accessToken = $accessToken;
    $this->accessSecret = $accessSecret;
  }

  /**
   * generate oauth-Signature 
   * based on https://developer.twitter.com/en/docs/basics/authentication/oauth-1-0a/creating-a-signature
   *
   * @param  array $params
   *
   * @return string
   */
  private function generateSignature($params)
  {
    $tempArr = [];
    ksort($params);
    foreach ($params as $key => $value) {
      $tempArr[] = "$key=" . rawurlencode($value);
    }
    $baseStr = "GET&" . rawurlencode($this->baseUrl) . '&' . rawurlencode(implode('&', $tempArr));

    $composite_key = rawurlencode($this->consumerSecret) . '&' . rawurlencode($this->accessSecret);

    return base64_encode(hash_hmac('sha1', $baseStr, $composite_key, true));
  }

  /**
   * generate the curl Header
   *
   * @param  mixed $oauth
   *
   * @return string
   */
  private function generateHeader($oauth)
  {
    $str = 'Authorization: OAuth ';
    $values = [];
    foreach ($oauth as $key => $value)
      $values[] = "$key=\"" . rawurlencode($value) . "\"";
    $str .= implode(', ', $values);
    return $str;
  }

  /**
   * config the curl options
   *
   * @param  array $query - the paramaters
   *
   * @return array
   */
  private function setupCurl($query)
  {
    $oauth = array(
      'oauth_consumer_key' => $this->consumerKey,
      'oauth_nonce' => time(),
      'oauth_signature_method' => 'HMAC-SHA1',
      'oauth_token' => $this->accessToken,
      'oauth_timestamp' => time(),
      'oauth_version' => '1.0'
    );
    $params = empty($query) ? $oauth : array_merge($query, $oauth);
    $oauth['oauth_signature'] = $this->generateSignature($params);
    $header = array($this->generateHeader($oauth), 'Expect:');
    $url = $this->baseUrl . "?" . http_build_query($query);
    $options = array(
      CURLOPT_HTTPHEADER => $header,
      CURLOPT_HEADER => false,
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_SSL_VERIFYPEER => false
    );
    return $options;
  }

  /**
   * search Twitte by keywards and 
   * "#", "@" or free text
   *
   * @param  mixed $keywords
   * @param  mixed $option
   *
   * @return json
   */
  public function searchTwitte($keywords, $option = "")
  {
    if ($keywords == '') {
      return null;
    }
    $keywords = $option . $keywords;

    //serach the maximum 100 recent twittes 
    $query = array('count' => 100, 'q' => $keywords, "result_type" => "recent" );
    //return full text
    $query['tweet_mode'] = 'extended';
    $options = $this->setupCurl($query);
    $feed = curl_init();
    curl_setopt_array($feed, $options);

    $json = curl_exec($feed);
    curl_close($feed);
    return  json_decode($json);
  }

  /**
   * return object response from API
   *
   * @param  mixed $keywords
   * @param  mixed $option
   *
   * @return object
   */
  public function twitteResponse($keywords, $option = "")
  {
    $json = $this->searchTwitte($keywords, $option);

    if(isset($json->errors)){
      return false;
    }
    $twittes = [];
    
    // build the twitte object array
    if ($json && !empty($json)) {
      if (isset($json->statuses)) {
        foreach ($json->statuses as $singleTwitte) {
          $twitte = new Twitte();
          $twitte->setAvatar($singleTwitte->user->profile_image_url);
          $twitte->setText($singleTwitte->full_text);
         
          // $twitte->setText($singleTwitte->text);
          $twitte->setTime($singleTwitte->created_at);
          $twitte->setName($singleTwitte->user->name);
          if(isset($singleTwitte->retweeted_status)){
            $twitte->setReText($singleTwitte->retweeted_status->full_text);
            // $twitte->setReText($singleTwitte->retweeted_status->text);
          }
          $twitte->setName($singleTwitte->user->name);
          $twittes[] = $twitte;
        }
      }
    }
    return $twittes;
  }

}
