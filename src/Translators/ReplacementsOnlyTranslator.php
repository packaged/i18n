<?php
namespace Packaged\I18n\Translators;

class ReplacementsOnlyTranslator extends AbstractTranslator
{
  public function _($text, array $replacements = null): string
  {
    return $this->_applyReplacements($text, $replacements);
  }

  public function _p($singular, $plural, int $n, array $replacements = null): string
  {
    return $this->_applyReplacements($n == 1 ? $singular : $plural, $replacements);
  }
}
