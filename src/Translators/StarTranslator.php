<?php
namespace Packaged\I18n\Translators;

use Packaged\I18n\Catalog\Message;

class StarTranslator extends AbstractTranslator
{
  const CHAR_STAR = '*';
  const CHAR_HASH = '#';

  protected string $_replacement = self::CHAR_STAR;

  public function __construct(string $style = self::CHAR_STAR)
  {
    $this->_replacement = $style;
  }

  public function _($msgId, $default, array $replacements = null, $choice = null): string
  {
    return $this->_applyReplacements(
      $this->_applyStars(Message::fromDefault($default)->getText($choice)),
      $replacements
    );
  }

  protected function _applyStars($text): string
  {
    return preg_replace_callback(
      '/(?!\{)\b(\w+)\b(?!\})/',
      function ($str) { return str_repeat($this->_replacement, strlen($str[0])); },
      $text
    );
  }
}
