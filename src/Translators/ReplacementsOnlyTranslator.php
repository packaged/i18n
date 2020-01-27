<?php
namespace Packaged\I18n\Translators;

class ReplacementsOnlyTranslator extends AbstractTranslator
{
  public function _($msgId, $default, array $replacements = null, $choice = null): string
  {
    return $this->_applyReplacements($this->_selectChoice($default, $choice), $replacements);
  }
}
