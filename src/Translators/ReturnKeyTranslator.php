<?php

namespace Packaged\I18n\Translators;

use Packaged\I18n\Translatable;

class ReturnKeyTranslator implements Translatable
{
  public function _($msgId, $default, array $replacements = null, $choice = null): string
  {
    return $msgId;
  }

  public function _p(string $msgId, string $singular, string $plural, int $n, array $replacements = null)
  {
    return $msgId;
  }

  public function _sp(string $msgId, string $simplePlural, int $n, array $replacements = null)
  {
    return $msgId;
  }
}
