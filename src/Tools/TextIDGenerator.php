<?php
namespace Packaged\I18n\Tools;

class TextIDGenerator
{
  protected int $_minWord = 3;
  protected int $_prefixLength = 30;
  protected bool $_appendBaseTime = false;

  protected static array $_cache = [];

  public function generateId(string $text): string
  {
    if(isset(static::$_cache[$text]))
    {
      return static::$_cache[$text];
    }

    if(strlen($text) > $this->_prefixLength)
    {
      $text1 = preg_replace('/\b\w{0,' . $this->_minWord . '}\b|\s/', ' ', $text);
    }
    else
    {
      $text1 = $text;
    }

    $long = str_word_count($text) > 3;
    $result = strtolower(trim(substr(preg_replace('/[^\w]+/', '_', $text1), 0, $this->_prefixLength), '_')) . '_' .
      ($this->_appendBaseTime && $long ?
        base_convert(time(), 10, 36) : substr(md5($text), 0, $long ? 6 : 4) . ($long ? '_' . strlen($text) : ''));

    static::$_cache[$text] = $result;
    return $result;
  }

  public static function generate(string $text): string
  {
    return (new static())->generateId($text);
  }
}
