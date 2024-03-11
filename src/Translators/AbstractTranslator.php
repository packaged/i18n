<?php
namespace Packaged\I18n\Translators;

use Packaged\I18n\Translatable;

abstract class AbstractTranslator implements Translatable
{
  protected function _applyReplacements(string $text, array $replacements = null): string
  {
    if(empty($replacements))
    {
      return $text;
    }

    foreach($replacements as $key => $value)
    {
      $text = str_replace('{' . $key . '}', $value, $text);
    }

    return $text;
  }
}
