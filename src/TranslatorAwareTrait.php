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
}
