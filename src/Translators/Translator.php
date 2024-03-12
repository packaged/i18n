<?php

namespace Packaged\I18n\Translators;

use Packaged\I18n\Tools\TextIDGenerator;
use Packaged\I18n\Translatable;

class Translator implements Translatable
{
  protected Translatable $_translator;
  protected string $_locale = 'en';

  const DEFAULT_LANGUAGE = 'en';

  protected static array $_pluralReplacements = [
    '(s)'  => 's',
    '(fe)' => 'ves',
    '(o)'  => 'oes',
  ];

  public static function with(Translatable $translator = null, string $locale = self::DEFAULT_LANGUAGE): self
  {
    return new static($translator);
  }

  final public function __construct(Translatable $translator = null, string $locale = self::DEFAULT_LANGUAGE)
  {
    $this->_translator = $translator ?? new ReplacementsOnlyTranslator();
    $this->_locale = $locale;
  }

  public function setLocale(string $locale): self
  {
    $this->_locale = $locale;
    return $this;
  }

  public function getLocale(): string
  {
    return $this->_locale;
  }

  public function _($msgId, $default, array $replacements = null, $choice = null): string
  {
    return $this->_translator->_($msgId, $default, $replacements, $choice);
  }

  public function _p(string $msgId, string $singular, string $plural, int $n, array $replacements = null): string
  {
    return $this->_translator->_(
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

  public function text(string $text, array $replacements = null, $choice = null): string
  {
    return $this->_translator->_((new TextIDGenerator())->generateId($text), $text, $replacements, $choice);
  }

  public function plural(string $singular, string $plural, int $n, array $replacements = null): string
  {
    return $this->_p(
      (new TextIDGenerator())->generateId($singular . $plural),
      $singular,
      $plural,
      $n,
      $replacements,
    );
  }

  /**
   * @param string     $simplePlural - shortcut for plural(text,texts,n)  written as simplural("text(s)",n)
   * @param int        $n
   * @param array|null $replacements
   *
   * @return string
   */
  public function simplural(string $simplePlural, int $n, array $replacements = null): string
  {
    return $this->_sp(
      (new TextIDGenerator())->generateId($simplePlural),
      $simplePlural,
      $n,
      $replacements
    );
  }
}
