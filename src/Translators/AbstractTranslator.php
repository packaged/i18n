<?php
namespace Packaged\I18n\Translators;

abstract class AbstractTranslator implements Translator
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

  protected static array $_pluralReplacements = [
    '(s)'  => 's',
    '(fe)' => 'ves',
    '(o)'  => 'oes',
  ];

  public function _p(string $msgId, string $singular, string $plural, int $n, array $replacements = null): string
  {
    return $this->_(
      $msgId,
      [1 => $singular, 'n..0,2..n' => $plural],
      $replacements,
      $n
    );
  }

  /**
   * @param string     $msgId
   * @param string     $simplePlural - shortcut for _p(text,texts,n)  written as _sp("text(s)",n)
   * @param int        $n
   * @param array|null $replacements
   *
   * @return string
   */
  public function _sp(string $msgId, string $simplePlural, int $n, array $replacements = null): string
  {
    return $this->_p(
      $msgId,
      str_replace(array_keys(static::$_pluralReplacements), '', $simplePlural),
      str_replace(
        array_keys(static::$_pluralReplacements),
        array_values(static::$_pluralReplacements),
        $simplePlural
      ),
      $n,
      $replacements
    );
  }
}
