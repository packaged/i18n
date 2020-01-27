<?php

namespace Tests\Translators;

use Packaged\I18n\Translators\ReplacementsOnlyTranslator;
use PHPUnit\Framework\TestCase;
use Tests\Supporting\TranslatedTextTestClass;

class ReplacementsOnlyTranslatorTest extends TestCase
{
  protected function _getTester()
  {
    return $class = new TranslatedTextTestClass(new ReplacementsOnlyTranslator());
  }

  public function test_()
  {
    $class = $this->_getTester();
    $mid = 'filecount';
    $opts = [
      '[1]You have {fileCount} file|You have {fileCount} files',
      '[1]You have {fileCount} file|[n..5]You have {fileCount} files',
      '[1]You have {fileCount} file|[2,n]You have {fileCount} files',
      '[1]You have {fileCount} file|[n]You have {fileCount} files',
    ];
    foreach($opts as $opt)
    {
      $this->assertEquals('You have 0 files', $class->_($mid, $opt, ['fileCount' => 0], 0));
      $this->assertEquals('You have 1 file', $class->_($mid, $opt, ['fileCount' => 1], 1));
      $this->assertEquals('You have 2 files', $class->_($mid, $opt, ['fileCount' => 2], 2));
    }

    $opt = 'Single Option {fileCount}';
    $this->assertEquals('Single Option 0', $class->_($mid, $opt, ['fileCount' => 0], 0));
    $this->assertEquals('Single Option 1', $class->_($mid, $opt, ['fileCount' => 1], 1));
    $opt = '';
    $this->assertEquals('', $class->_($mid, $opt, ['fileCount' => 0], 0));

    $this->assertEquals(123, $class->_($mid, 123, ['fileCount' => 0], 0));
  }

  public function test_p()
  {
    $class = $this->_getTester();
    $msg = 'You have {fileCount} file';
    $msg2 = 'You have {fileCount} files';
    $this->assertEquals('You have 0 files', $class->_p($msg, $msg2, 0, ['fileCount' => 0]));
    $this->assertEquals('You have 1 file', $class->_p($msg, $msg2, 1, ['fileCount' => 1]));
    $this->assertEquals('You have 2 files', $class->_p($msg, $msg2, 2, ['fileCount' => 2]));
  }

  public function test_sp()
  {
    $class = $this->_getTester();
    $msg = 'You have {fileCount} file(s)';
    $this->assertEquals('You have 0 files', $class->_sp($msg, 0, ['fileCount' => 0]));
    $this->assertEquals('You have 1 file', $class->_sp($msg, 1, ['fileCount' => 1]));
    $this->assertEquals('You have 2 files', $class->_sp($msg, 2, ['fileCount' => 2]));
    $this->assertEquals('You have 3 files', $class->_sp($msg, 3, ['fileCount' => 3]));
  }

  public function test_t()
  {
    $class = $this->_getTester();
    $this->assertEquals('Hello John', $class->_t('Hello {name}', ['name' => 'John']));
    $this->assertEquals('Hello {name}', $class->_t('Hello {name}', []));
  }
}
