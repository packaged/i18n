<?php
namespace Packaged\I18n\Translators;

use Packaged\I18n\Catalog\Message;

class ReplacementsOnlyTranslator extends AbstractTranslator
{
  public function _($msgId, $default, array $replacements = null, $choice = null)
  {
    return $this->_applyReplacements(Message::fromDefault($default)->getText($choice), $replacements);
  }
}
