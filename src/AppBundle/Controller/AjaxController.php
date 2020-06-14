<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Service\TwitterService;
use AppBundle\Service\FormatService;

class AjaxController extends Controller
{
    /**
     * @Route("/search", name="search")
     */
    public function searchAction(Request $request)
    {

        if ($request->isXmlHttpRequest()) {
            $keywords = $request->get('keywords');
            $option = $request->get('option');

            $result = $this->get(TwitterService::class)->twitteResponse($keywords, $option);
            //check auth
            if($result === false){
                return new JsonResponse('<h3>Could not authenticate you.</h3>');
            }else if(empty($result)){
                return new JsonResponse('<h3>No Results</h3>');
            }
            $data = $this->get(FormatService::class)->formatTwittes($result);
            
            return new JsonResponse($data);
        }
        
    }

}
