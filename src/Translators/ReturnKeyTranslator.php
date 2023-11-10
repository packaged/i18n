<?php

namespace Packaged\I18n\Translators;

class ReturnKeyTranslator implements Translator
{
  public function _($msgId, $default, array $replacements = null, $choice = null)
  {
    return $msgId;
  }
}
