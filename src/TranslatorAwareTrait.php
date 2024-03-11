<?php

namespace Packaged\I18n;

use Packaged\I18n\Translators\ReplacementsOnlyTranslator;

trait TranslatorAwareTrait
{
  private ?Translatable $_translator;

  public function setTranslator(Translatable $translator): self
  {
    $this->_translator = $translator;
    return $this;
  }

  public function getTranslator(): Translatable
  {
    return $this->_translator ?? new ReplacementsOnlyTranslator();
  }

  public function hasTranslator(): bool
  {
    return $this->_translator !== null;
  }
}
