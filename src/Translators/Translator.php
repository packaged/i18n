<?php

namespace Packaged\I18n\Translators;

use Packaged\I18n\Translatable;

interface Translator extends Translatable
{
  /**
   * @param string     $msgId
   * @param            $default
   * @param array|null $replacements
   * @param mixed      $choice
   *
   * @return string
   */
  public function _(string $msgId, $default, array $replacements = null, $choice = null): string;

  public function _p(string $msgId, string $singular, string $plural, int $n, array $replacements = null): string;

  public function _sp(string $msgId, string $simplePlural, int $n, array $replacements = null): string;
}
