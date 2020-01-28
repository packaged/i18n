<?php
namespace Packaged\I18n\Translators;

abstract class AbstractTranslator implements Translator
{
  protected function _applyReplacements(string $text, array $replacements = null): string
  {
    if($replacements === null || empty($replacements))
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
