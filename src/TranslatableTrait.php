<?php

namespace Packaged\I18n;

use Packaged\I18n\Translators\Translator;

trait TranslatableTrait
{
  abstract protected function _getTranslator(): Translator;

  private static $replacements = [
    '(s)'  => 's',
    '(fe)' => 'ves',
    '(o)'  => 'oes',
  ];

  public function _($msgId, $default, array $replacements = null, $choice = null)
  {
    return $this->_getTranslator()->_($msgId, $default, $replacements, $choice);
  }

  public function _t($text, array $replacements = null, $choice = null)
  {
    return $this->_getTranslator()->_(md5($text), $text, $replacements, $choice);
  }

  public function _p($singular, $plural, int $n, array $replacements = null)
  {
    return $this->_getTranslator()->_(
      md5($singular . $plural),
      [1 => $singular, 'n..0,2..n' => $plural],
      $replacements,
      $n
    );
  }

  /**
   * @param string     $simplePlural - shortcut for _p(text,texts,n)  written as _sp("text(s)",n)
   * @param int        $n
   * @param array|null $replacements
   *
   * @return mixed
   */
  public function _sp($simplePlural, int $n, array $replacements = null)
  {
    return $this->_p(
      str_replace(array_keys(static::$replacements), '', $simplePlural),
      str_replace(
        array_keys(static::$replacements),
        array_values(static::$replacements),
        $simplePlural
      ),
      $n,
      $replacements
    );
  }
}
