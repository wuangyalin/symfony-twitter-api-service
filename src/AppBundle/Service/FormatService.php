<?php

namespace AppBundle\Service;

/**
 * Format Service, return html content
 *
 * Class ReasonService
 * @package AppBundle\Service
 */
class FormatService
{
  /**
   * Format twiiter response
   *
   * @param  mixed $keywords
   * @param  mixed $option
   *
   * @return object
   */
  public function formatTwittes($twittes)
  {
      $div = '';
      foreach($twittes as $twitte){
        $single_div = '
        <div class="card">
            <div class="card-body">
                <img src="'.$twitte->getAvatar().'" class="float-left rounded-circle">
                <div class="message">
                    <h5 class="card-title">'.$twitte->getName().'</h5>
                    <h6 class="card-subtitle mb-2 text-muted">'.$twitte->getTime().'</h6>
                    <p class="card-text">'.$twitte->getText().'</p>';
        if($twitte->getReText()){
          $single_div = $single_div.'<mark><small>------Retwitte message: '.$twitte->getReText().'</small></mark>';
        }
        $single_div =$single_div.'
                </div>
            </div>
        </div>
        ';
        $div = $div.$single_div;
      }
      return $div;
  }

}
