<?php
namespace Packaged\I18n;

interface Translatable
{
  /**
   * @param string       $msgId        Message ID to translate
   * @param string|array $default      Default text to display, or options to select
   * @param array|null   $replacements string replacements '{arrayKey}'
   * @param null         $choice       e.g. quantity, gender
   *
   * @return string
   */
  public function _($msgId, $default, array $replacements = null, $choice = null): string;
}
