<?php

namespace Packaged\I18n;

use Packaged\I18n\Translators\Translator;

interface TranslatorAware
{
  public function setTranslator(Translator $translator): self;

  public function getTranslator(): Translator;

  public function hasTranslator(): bool;
}
