<?php
namespace Packaged\I18n;

interface SimplifiedTranslatable
{
  public function _t($text, array $replacements = null, $choice = null): string;

  public function _p($singular, $plural, int $n, array $replacements = null): string;

  public function _sp($simplePlural, int $n, array $replacements = null): string;
}
