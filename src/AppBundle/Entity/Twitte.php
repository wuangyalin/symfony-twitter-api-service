<?php

namespace AppBundle\Entity;

/**
 * Build the attribute to display a twitte
 *
 * @package AppBundle\Entity
 */
class Twitte
{
  private $time;
  private $text;
  private $avatar;
  private $name;
  //retwitte text, sometimes api search keywords are in the original twitte
  private $reText;


  /**
   * Get the value of avatar
   */
  public function getAvatar()
  {
    return $this->avatar;
  }

  /**
   * Set the value of avatar
   *
   * @return  self
   */
  public function setAvatar($avatar)
  {
    $this->avatar = $avatar;

    return $this;
  }

  /**
   * Get the value of text
   */
  public function getText()
  {
    return $this->text;
  }

  /**
   * Set the value of text
   *
   * @return  self
   */
  public function setText($text)
  {
    $this->text = $text;

    return $this;
  }

  /**
   * Get the value of time
   */
  public function getTime()
  {
    return $this->time;
  }

  /**
   * Set the value of time
   *
   * @return  self
   */
  public function setTime($time)
  {
    $this->time = $time;

    return $this;
  }

  /**
   * Get the value of name
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Set the value of name
   *
   * @return  self
   */
  public function setName($name)
  {
    $this->name = $name;

    return $this;
  }

  /**
   * Get the value of reText
   */ 
  public function getReText()
  {
    return $this->reText;
  }

  /**
   * Set the value of reText
   *
   * @return  self
   */ 
  public function setReText($reText)
  {
    $this->reText = $reText;

    return $this;
  }
}
