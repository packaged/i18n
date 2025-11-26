<?php

namespace Packaged\I18n\Translators;

class ReturnKeyTranslator extends AbstractTranslator
{
  public function _($msgId, $default, array $replacements = null, $choice = null): string
  {
    return $msgId;
  }
}
