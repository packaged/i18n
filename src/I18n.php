<?php

namespace Packaged\I18n;

use Packaged\I18n\Tools\TextIDGenerator;
use Packaged\I18n\Translators\ReplacementsOnlyTranslator;
use Packaged\I18n\Translators\Translator;

class I18n
{
  protected Translator $_translator;

  public static function with(Translator $translator = null): self
  {
    return new static($translator);
  }

  final public function __construct(Translator $translator = null)
  {
    $this->_translator = $translator ?? new ReplacementsOnlyTranslator();
  }

  public function text(string $text, array $replacements = null, $choice = null): string
  {
    return $this->_translator->_((new TextIDGenerator())->generateId($text), $text, $replacements, $choice);
  }

  public function plural(string $singular, string $plural, int $n, array $replacements = null): string
  {
    return $this->_translator->_p(
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
    return $this->_translator->_sp(
      (new TextIDGenerator())->generateId($simplePlural),
      $simplePlural,
      $n,
      $replacements
    );
  }
}
