<?php
namespace Packaged\I18n;

interface Translatable
{
  public function _($text, array $replacements = null): string;

  public function _p($singular, $plural, int $n, array $replacements = null): string;
}
