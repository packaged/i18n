<?php

namespace Packaged\I18n;

interface Translatable
{
  public function _($text, array $args = null): string;

  public function _p($singular, $plural, int $n, array $args = null): string;

  /**
   * @param string $simplePlural - shortcut for _p(text,texts,n)  written as _sp(text(s),n)
   * @param int $n
   * @param array|null $args
   * @return mixed
   */
  public function _sp($simplePlural, int $n, array $args = null): string;

  public function _c($text, $plural, $n, array $args = null): string;
}