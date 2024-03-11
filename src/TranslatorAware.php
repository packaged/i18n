<?php

namespace Packaged\I18n;

interface TranslatorAware
{
  public function setTranslator(Translatable $translator): self;

  public function getTranslator(): Translatable;

  public function hasTranslator(): bool;
}
