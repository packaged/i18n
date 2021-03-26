<?php
namespace Packaged\I18n;

interface SimplifiedTranslatable
{
  public function _t($text, array $replacements = null, $choice = null);

  public function _p($singular, $plural, int $n, array $replacements = null);

  public function _sp($simplePlural, int $n, array $replacements = null);
}
