<?php
namespace Tests\Supporting;

use Packaged\I18n\Translatable;
use Packaged\I18n\TranslatableTrait;
use Packaged\I18n\Translators\Translator;

class TranslatedTextTestClass implements Translatable
{
  public function __construct(Translator $translator)
  {
    $this->_translator = $translator;
  }

  use TranslatableTrait;

  protected $_translator;

  /**
   * @param Translator $translator
   *
   * @return TranslatedTextTestClass
   */
  public function setTranslator(Translator $translator): TranslatedTextTestClass
  {
    $this->_translator = $translator;
    return $this;
  }

  protected function getTranslator(): Translatable
  {
    return $this->_translator;
  }
}
