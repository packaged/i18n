<?php
namespace Packaged\I18n\Tools;

class TextIDGenerator
{
  protected $_minWord = 3;
  protected $_prefixLength = 30;
  protected $_appendBaseTime = false;

  public function generateId($text)
  {
    if(strlen($text) > $this->_prefixLength)
    {
      $text1 = preg_replace('/\b\w{0,' . $this->_minWord . '}\b|\s/', ' ', $text);
    }
    else
    {
      $text1 = $text;
    }

    $long = str_word_count($text) > 3;
    return strtolower(trim(substr(preg_replace('/[^\w]+/', '_', $text1), 0, $this->_prefixLength), '_')) . '_' .
      ($this->_appendBaseTime && $long ?
        base_convert(time(), 10, 36) : substr(md5($text), 0, $long ? 6 : 4) . ($long ? '_' . strlen($text) : ''));
  }

  public static function generate($text)
  {
    return (new static())->generateId($text);
  }
}
