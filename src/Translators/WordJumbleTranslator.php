<?php
namespace Packaged\I18n\Translators;

use Packaged\I18n\Catalog\Message;

class WordJumbleTranslator extends AbstractTranslator
{
  const STYLE_RSORT = 'rsort';
  const STYLE_SORT = 'sort';
  const STYLE_SHUFFLE = 'shuffle';

  protected $_style;

  public function __construct($style = self::STYLE_SHUFFLE)
  {
    $this->_style = $style;
  }

  public function _($msgId, $default, array $replacements = null, $choice = null): string
  {
    return $this->_applyReplacements($this->_jumble(Message::fromDefault($default)->getText($choice)), $replacements);
  }

  protected function _jumble($text)
  {
    return preg_replace_callback(
      '/(?!\{)\b(\w+)\b(?!\})/',
      function ($str) {
        $chars = str_split($str[0]);

        $first = array_shift($chars);
        $last = array_pop($chars);
        switch($this->_style)
        {
          case self::STYLE_SHUFFLE:
            shuffle($chars);
            break;
          case self::STYLE_RSORT:
            rsort($chars);
            break;
          case self::STYLE_SORT:
            sort($chars);
            break;
        }
        return $first . implode('', $chars) . $last;
      },
      $text
    );
  }
}
