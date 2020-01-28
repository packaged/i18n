<?php
namespace Packaged\I18n\Catalog;

use Packaged\I18n\Translators\Translator;

class Message
{
  protected $_text;
  /**
   * @var array|string
   */
  protected $_options;
  protected $_scalarOptions = false;
  protected $_choice;
  protected $_choiceNumeric;

  public function __construct(?string $text, $options = null)
  {
    $this->_scalarOptions = is_scalar($options);
    if(!$this->_scalarOptions && !is_array($options))
    {
      throw new \Exception("Message Options must be either string or array");
    }
    $this->_text = $text;
    $this->_options = $options;
  }

  public static function create(?string $text, $options = null)
  {
    return new static($text, $options);
  }

  protected function _setChoice($choice = null)
  {
    if($choice === null)
    {
      $this->_choice = null;
      $this->_choiceNumeric = null;
    }
    else
    {
      $this->_choiceNumeric = is_int($choice) || ctype_digit($choice);
      $this->_choice = $this->_choiceNumeric ? (int)$choice : $choice;
    }
  }

  public function getText($choice = null): string
  {
    $this->_setChoice($choice);
    return $choice === null && $this->_text ? $this->_text : $this->_selectChoice();
  }

  protected function _convertStringOptions()
  {
    if($this->_scalarOptions)
    {
      $matches = [];
      preg_match_all('/((\[[^\]]+\])?([^\|]+))+/m', $this->_options, $matches);
      if(isset($matches[3]))
      {
        foreach($matches[2] as $i => $key)
        {
          $key = empty($key) ? Translator::DEFAULT_OPTION : substr($key, 1, -1);
          yield $key => $matches[3][$i];
        }
      }
    }
    return null;
  }

  protected function _selectChoice(): string
  {
    $return = '';
    foreach($this->_scalarOptions ? $this->_convertStringOptions() : $this->_options as $key => $v)
    {
      if($key === Translator::DEFAULT_OPTION)
      {
        $return = $v;
      }
      else if($this->_choiceMatches($key))
      {
        return $v;
      }
    }
    return $return;
  }

  protected function _choiceMatches($optionKey)
  {
    if($optionKey === '' || ($this->_choiceNumeric && $optionKey === 'n')
      || $optionKey === $this->_choice)
    {
      return true;
    }

    foreach(explode(',', $optionKey) as $key)
    {
      if($this->_choiceNumeric)
      {
        if($key === 'n' || ((is_int($key) || ctype_digit($key)) && (int)$key === $this->_choice))
        {
          return true;
        }
        if(strpos($key, '..') > 0)
        {
          [$min, $max] = explode('..', $key);
          if(($min === 'n' || $this->_choice >= $min) && ($max === 'n' || $this->_choice <= $max))
          {
            return true;
          }
        }
      }
      else if($key === $this->_choice)
      {
        return true;
      }
    }

    return false;
  }
}
