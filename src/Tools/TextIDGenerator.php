<?php
namespace Packaged\I18n\Tools;

class TextIDGenerator
{
  protected $_minWord = 3;
  protected $_prefixLength = 30;
  protected $_appendBaseTime = true;

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

    $shortText = preg_replace('/[^\w]+/', '_', $text1);
    return strtolower(trim(substr($shortText, 0, $this->_prefixLength), '_')) .
      ($this->_appendBaseTime && str_word_count($text) > 3 ? '_' . base_convert(time(), 10, 36) : '');
  }
}
