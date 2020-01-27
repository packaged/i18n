<?php
namespace Packaged\I18n\Translators;

abstract class AbstractTranslator implements Translator
{
  protected function _applyReplacements(string $text, array $replacements = null): string
  {
    if($replacements === null || empty($replacements))
    {
      return $text;
    }

    foreach($replacements as $key => $value)
    {
      $text = str_replace('{' . $key . '}', $value, $text);
    }

    return $text;
  }

  protected function _selectChoice($options, $choice): string
  {
    if(is_string($options))
    {
      $matches = [];
      preg_match_all('/((\[[^\]]+\])?([^\|]+))+/m', $options, $matches);
      if(isset($matches[3]))
      {
        foreach($matches[2] as $i => $key)
        {
          $key = empty($key) ? Translator::DEFAULT_OPTION : substr($key, 1, -1);
          if($this->_choiceMatches($key, $choice))
          {
            return $matches[3][$i];
          }
        }
      }
      return $options;
    }
    else if(is_array($options))
    {
      foreach($options as $key => $v)
      {
        if($this->_choiceMatches($key, $choice))
        {
          return $v;
        }
      }
    }
    return $options;
  }

  protected function _choiceMatches($optionKey, $choice)
  {
    if($optionKey === '' || $optionKey === Translator::DEFAULT_OPTION || $optionKey == $choice || $optionKey === 'n')
    {
      return true;
    }

    foreach(explode(',', $optionKey) as $key)
    {
      if($key === $choice || ($key === 'n' && is_numeric($choice)))
      {
        return true;
      }
      if(strpos($key, '..') > 0)
      {
        [$min, $max] = explode('..', $key);
        if(($min === 'n' || $choice >= $min) && ($max === 'n' || $choice <= $max))
        {
          return true;
        }
      }
    }

    return false;
  }
}
