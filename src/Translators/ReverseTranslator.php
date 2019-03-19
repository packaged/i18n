<?php
namespace Packaged\I18n\Translators;

class ReverseTranslator extends AbstractTranslator
{
  public function _($text, array $replacements = null): string
  {
    return $this->_applyReplacements($this->_reverse($text), $replacements);
  }

  public function _p($singular, $plural, int $n, array $replacements = null): string
  {
    return $this->_applyReplacements($this->_reverse($n == 1 ? $singular : $plural), $replacements);
  }

  protected function _reverse($text)
  {
    return preg_replace_callback('/(?!\{)\b(\w+)\b(?!\})/', function ($str) { return strrev($str[0]); }, $text);
  }
}
