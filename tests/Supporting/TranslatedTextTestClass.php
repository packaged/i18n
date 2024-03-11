<?php
namespace Tests\Supporting;

use Packaged\I18n\Translatable;
use Packaged\I18n\Translators\Translator;

class TranslatedTextTestClass
{
  public function __construct(Translatable $translator)
  {
    $this->_translator = $translator;
  }

  protected Translatable $_translator;

  /**
   * @param Translator $translator
   *
   * @return TranslatedTextTestClass
   */
  public function setTranslator(Translatable $translator): TranslatedTextTestClass
  {
    $this->_translator = $translator;
    return $this;
  }

  public function _($msgId, $default, array $replacements = null, $choice = null): string
  {
    return Translator::with($this->_translator)->_($msgId, $default, $replacements, $choice);
  }

  public function _p(string $msgId, string $singular, string $plural, int $n, array $replacements = null): string
  {
    return Translator::with($this->_translator)->_p($msgId, $singular, $plural, $n, $replacements);
  }

  public function _sp(string $msgId, string $simplePlural, int $n, array $replacements = null): string
  {
    return Translator::with($this->_translator)->_sp($msgId, $simplePlural, $n, $replacements);
  }

  public function text($default, array $replacements = null, $choice = null): string
  {
    return Translator::with($this->_translator)->text($default, $replacements, $choice);
  }

  public function plural(string $singular, string $plural, int $n, array $replacements = null): string
  {
    return Translator::with($this->_translator)->plural($singular, $plural, $n, $replacements);
  }

  public function simplural(string $simplePlural, int $n, array $replacements = null): string
  {
    return Translator::with($this->_translator)->simplural($simplePlural, $n, $replacements);
  }
}
