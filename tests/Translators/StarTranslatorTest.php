<?php
namespace Tests\Translators;

use Packaged\I18n\Translators\StarTranslator;
use PHPUnit\Framework\TestCase;
use Tests\Supporting\TranslatedTextTestClass;

class StarTranslatorTest extends TestCase
{
  protected function _getTester()
  {
    return $class = new TranslatedTextTestClass(new StarTranslator());
  }

  public function test_p()
  {
    $class = $this->_getTester();
    $msg = 'You have {fileCount} file';
    $msg2 = 'You have {fileCount} files';
    $this->assertEquals('*** **** 0 *****', $class->_p($msg, $msg2, 0, ['fileCount' => 0]));
    $this->assertEquals('*** **** 1 ****', $class->_p($msg, $msg2, 1, ['fileCount' => 1]));
    $this->assertEquals('*** **** 2 *****', $class->_p($msg, $msg2, 2, ['fileCount' => 2]));
  }

  public function test_sp()
  {
    $class = $this->_getTester();
    $msg = 'You have {fileCount} file(s)';
    $this->assertEquals('*** **** 0 *****', $class->_sp($msg, 0, ['fileCount' => 0]));
    $this->assertEquals('*** **** 1 ****', $class->_sp($msg, 1, ['fileCount' => 1]));
    $this->assertEquals('*** **** 2 *****', $class->_sp($msg, 2, ['fileCount' => 2]));
    $this->assertEquals('*** **** 3 *****', $class->_sp($msg, 3, ['fileCount' => 3]));
  }

  public function test_t()
  {
    $class = $this->_getTester();
    $this->assertEquals('***** John', $class->_t('Hello {name}', ['name' => 'John']));
  }
}
