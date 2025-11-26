<?php
namespace Packaged\I18n\Catalog;

class Message
{
  const DEFAULT_OPTION = '_';
  const META_OPTION = '__meta';

  /** @var array<string, string|array<string>> */
  protected array $_options;
  /**
   * @var string|int|null
   */
  protected $_choice = null;
  protected ?bool $_choiceNumeric = null;

  public function __construct(array $options = [])
  {
    $this->_options = $options;
  }

  public static function fromDefault($default): self
  {
    if(is_array($default))
    {
      return new static($default);
    }
    $msg = new static();
    if(is_scalar($default))
    {
      $matches = [];
      preg_match_all('/((\[[^\]]+\])?([^\|]+))+/m', (string)$default, $matches);
      /** @phpstan-ignore isset.offset */
      if(isset($matches[3]))
      {
        foreach($matches[2] as $i => $key)
        {
          $key = empty($key) ? self::DEFAULT_OPTION : substr($key, 1, -1);
          $msg->addOption($key, $matches[3][$i]);
        }
      }
    }
    return $msg;
  }

  /** @return string[] */
  public function getMeta(): array
  {
    return isset($this->_options[self::META_OPTION]) && is_array($this->_options[self::META_OPTION])
      ? $this->_options[self::META_OPTION] : [];
  }

  public function addOption($key, $value): self
  {
    $this->_options[$key] = $value;
    return $this;
  }

  /** @return array<string, string|array<string>> */
  public function getOptions(): array
  {
    return $this->_options;
  }

  protected function _setChoice($choice = null): void
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
    return $this->_selectChoice();
  }

  protected function _selectChoice(): string
  {
    $return = '';
    foreach($this->_options as $key => $v)
    {
      if($key === self::DEFAULT_OPTION)
      {
        $return = $v;
        if($this->_choice === null && is_string($return))
        {
          return $return;
        }
      }
      else if($this->_choiceMatches($key))
      {
        return is_string($v) ? $v : '';
      }
    }

    return is_string($return) ? $return : '';
  }

  protected function _choiceMatches($optionKey): bool
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
        if($key === 'n' || ((ctype_digit($key)) && (int)$key === $this->_choice))
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
