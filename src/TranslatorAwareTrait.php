<?php

namespace Packaged\I18n;

use Packaged\I18n\Translators\Translator;

trait TranslatorAwareTrait
{
  private ?Translator $_translator;

  public function setTranslator(Translator $translator): self
  {
    $this->_translator = $translator;
    return $this;
  }

  public function getTranslator(): Translator
  {
    return $this->_translator ?? new Translator();
  }

  public function hasTranslator(): bool
  {
    return $this->_translator !== null;
  }

  /** Pass through translator methods */

  public function _($msgId, $default, array $replacements = null, $choice = null): string
  {
    return $this->getTranslator()->_($msgId, $default, $replacements, $choice);
  }

  public function _p(string $msgId, string $singular, string $plural, int $n, array $replacements = null): string
  {
    return $this->getTranslator()->_p($msgId, $singular, $plural, $n, $replacements);
  }

  public function _sp(string $msgId, string $simplural, int $n, array $replacements = null): string
  {
    return $this->getTranslator()->_sp($msgId, $simplural, $n, $replacements);
  }
}
