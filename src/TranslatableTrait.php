<?php

namespace Packaged\I18n;

trait TranslatableTrait
{
  public function _($text, array $args = null): string
  {
    // TODO: Implement _() method.
  }

  public function _p($singular, $plural, int $n, array $args = null): string
  {
    // TODO: Implement _p() method.
  }

  public function _sp($simplePlural, int $n, array $args = null): string
  {
    return $this->_p(str_replace('(s)', '', $simplePlural), str_replace('(s)', 's', $simplePlural), $n, $args);
  }

  public function _c($text, $plural, $n, array $args = null): string
  {
    // TODO: Implement _c() method.
  }

}