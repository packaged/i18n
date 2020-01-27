<?php
namespace Packaged\I18n\Translators;

class StarTranslator extends AbstractTranslator
{
  const CHAR_STAR = '*';
  const CHAR_HASH = '#';

  protected $_replacement;

  public function __construct($style = self::CHAR_STAR)
  {
    $this->_replacement = $style;
  }

  public function _($msgId, $default, array $replacements = null, $choice = null): string
  {
    return $this->_applyReplacements($this->_applyStars($this->_selectChoice($default, $choice)), $replacements);
  }

  protected function _applyStars($text)
  {
    return preg_replace_callback(
      '/(?!\{)\b(\w+)\b(?!\})/',
      function ($str) { return str_repeat($this->_replacement, strlen($str[0])); },
      $text
    );
  }
}
