<?php

namespace Packaged\I18n;

use Packaged\I18n\Translators\Translator;

trait TranslatableTrait
{
  abstract protected function getTranslator(): Translator;

  public function _($text, array $replacements = null): string
  {
    return $this->getTranslator()->_($text, $replacements);
  }

  public function _p($singular, $plural, int $n, array $replacements = null): string
  {
    return $this->getTranslator()->_p($singular, $plural, $n, $replacements);
  }

  /**
   * @param string     $simplePlural - shortcut for _p(text,texts,n)  written as _sp("text(s)",n)
   * @param int        $n
   * @param array|null $replacements
   *
   * @return mixed
   */
  public function _sp($simplePlural, int $n, array $replacements = null): string
  {
    return $this->_p(str_replace('(s)', '', $simplePlural), str_replace('(s)', 's', $simplePlural), $n, $replacements);
  }
}
