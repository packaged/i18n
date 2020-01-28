<?php
namespace Packaged\I18n\Translators;

use Packaged\I18n\Catalog\Message;

class ReverseTranslator extends AbstractTranslator
{
  public function _($msgId, $default, array $replacements = null, $choice = null): string
  {
    return $this->_applyReplacements($this->_reverse(Message::fromDefault($default)->getText($choice)), $replacements);
  }

  protected function _reverse($text)
  {
    return preg_replace_callback('/(?!\{)\b(\w+)\b(?!\})/', function ($str) { return strrev($str[0]); }, $text);
  }
}
