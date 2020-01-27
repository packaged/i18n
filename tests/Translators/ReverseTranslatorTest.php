<?php

namespace Tests\Translators;

use Packaged\I18n\Translators\ReverseTranslator;
use PHPUnit\Framework\TestCase;
use Tests\Supporting\TranslatedTextTestClass;

class ReverseTranslatorTest extends TestCase
{
  protected function _getTester()
  {
    return $class = new TranslatedTextTestClass(new ReverseTranslator());
  }

  public function test_p()
  {
    $class = $this->_getTester();
    $msg = 'You have {fileCount} file';
    $msg2 = 'You have {fileCount} files';
    $this->assertEquals('uoY evah 0 selif', $class->_p($msg, $msg2, 0, ['fileCount' => 0]));
    $this->assertEquals('uoY evah 1 elif', $class->_p($msg, $msg2, 1, ['fileCount' => 1]));
    $this->assertEquals('uoY evah 2 selif', $class->_p($msg, $msg2, 2, ['fileCount' => 2]));
  }

  public function test_sp()
  {
    $class = $this->_getTester();
    $msg = 'You have {fileCount} file(s)';
    $this->assertEquals('uoY evah 0 selif', $class->_sp($msg, 0, ['fileCount' => 0]));
    $this->assertEquals('uoY evah 1 elif', $class->_sp($msg, 1, ['fileCount' => 1]));
    $this->assertEquals('uoY evah 2 selif', $class->_sp($msg, 2, ['fileCount' => 2]));
    $this->assertEquals('uoY evah 3 selif', $class->_sp($msg, 3, ['fileCount' => 3]));
  }

  public function test_t()
  {
    $class = $this->_getTester();
    $this->assertEquals('olleH John', $class->_t('Hello {name}', ['name' => 'John']));
  }
}
