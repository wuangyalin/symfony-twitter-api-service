## Twitter API service

Creating a web interface, using Symfnony 3.4, which searches the Twitter API for all tweets related to Sydney or whatever free text.
That includes use of #hashtags, mentions of @Sydney, and free form text searches.

### Prerequisites

- php 7.2
- composer
- apply the twiiter API access keys on https://developer.twitter.com/en/docs/basics/getting-started

### Install

```
composer install
```

### Run

```
php bin/console server:run
```

### Test

```
./vendor/bin/simple-phpunit
```

### Description
This is a single pgae web application, users are able to search twittes based on three different options:
1. Search twittes which include #Sydney.
2. Search twittes which mentioned user @Sydney.
3. Search twittes by any text.

The searched twittes including the following information:
1. Users' avatar.
2. The twitte posted time.
3. The twitte infomation.
4. Retwitte infomation if has. (Sometimes the search keywords are in the original twitte).

### Technologies Used
* Language: PHP7.2, Javascript, Twig
* Technology: Symfony 3.4 framework, JavaScript, Jquery, Ajax, Bootstrap 4.

### Screenshot

![Screenshot](https://github.com/wuangyalin/symfony-twitter-api-service/blob/master/web/screenshots/screenshot.png)
