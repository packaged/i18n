<?php
namespace Packaged\I18n\Translators;

class StarTranslator extends AbstractTranslator
{
  public function _($text, array $replacements = null): string
  {
    return $this->_applyReplacements($this->_applyStars($text), $replacements);
  }

  public function _p($singular, $plural, int $n, array $replacements = null): string
  {
    return $this->_applyReplacements($this->_applyStars($n == 1 ? $singular : $plural), $replacements);
  }

  protected function _applyStars($text)
  {
    return preg_replace_callback(
      '/(?!\{)\b(\w+)\b(?!\})/',
      function ($str) { return str_repeat('*', strlen($str[0])); },
      $text
    );
  }

}
