<?php
namespace Packaged\I18n\Translators;

class ReverseTranslator extends AbstractTranslator
{
  public function _($msgId, $default, array $replacements = null, $choice = null): string
  {
    return $this->_applyReplacements($this->_reverse($this->_selectChoice($default, $choice)), $replacements);
  }

  protected function _reverse($text)
  {
    return preg_replace_callback('/(?!\{)\b(\w+)\b(?!\})/', function ($str) { return strrev($str[0]); }, $text);
  }
}
