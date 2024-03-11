<?php

namespace Packaged\I18n\Translators;

use Packaged\I18n\Translatable;

class ReturnKeyTranslator implements Translatable
{
  public function _($msgId, $default, array $replacements = null, $choice = null)
  {
    return $msgId;
  }
}
