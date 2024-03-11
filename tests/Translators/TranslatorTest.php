<?php

namespace Tests\Translators;

use Packaged\I18n\Translators\ReplacementsOnlyTranslator;
use Packaged\I18n\Translators\Translator;
use PHPUnit\Framework\TestCase;
use Tests\Supporting\TranslatedTextTestClass;

class TranslatorTest extends TestCase
{
  protected function _getTester(): TranslatedTextTestClass
  {
    return new TranslatedTextTestClass(Translator::with(new ReplacementsOnlyTranslator()));
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

  public function test_options()
  {
    $class = $this->_getTester();
    $mid = 'gen';
    $opts = [
      'male'   => '{person} has invited you to his party!',
      'female' => '{person} has invited you to her party!',
      'other'  => '{person} has invited you to their party!',
    ];

    $this->assertEquals('Bob has invited you to his party!', $class->_($mid, $opts, ['person' => 'Bob'], 'male'));
    $this->assertEquals('Jane has invited you to her party!', $class->_($mid, $opts, ['person' => 'Jane'], 'female'));
    $this->assertEquals('Kai has invited you to their party!', $class->_($mid, $opts, ['person' => 'Kai'], 'other'));
  }

  public function test_p()
  {
    $class = $this->_getTester();
    $msg = 'You have {fileCount} file';
    $msg2 = 'You have {fileCount} files';
    $this->assertEquals('You have 0 files', $class->plural($msg, $msg2, 0, ['fileCount' => 0]));
    $this->assertEquals('You have 1 file', $class->plural($msg, $msg2, 1, ['fileCount' => 1]));
    $this->assertEquals('You have 2 files', $class->plural($msg, $msg2, 2, ['fileCount' => 2]));
  }

  public function test_sp()
  {
    $class = $this->_getTester();
    $msg = 'You have {fileCount} file(s)';
    $this->assertEquals('You have 0 files', $class->simplural($msg, 0, ['fileCount' => 0]));
    $this->assertEquals('You have 1 file', $class->simplural($msg, 1, ['fileCount' => 1]));
    $this->assertEquals('You have 2 files', $class->simplural($msg, 2, ['fileCount' => 2]));
    $this->assertEquals('You have 3 files', $class->simplural($msg, 3, ['fileCount' => 3]));
  }

  public function test_t()
  {
    $class = $this->_getTester();
    $this->assertEquals('Hello John', $class->text('Hello {name}', ['name' => 'John']));
    $this->assertEquals('Hello {name}', $class->text('Hello {name}', []));
  }

  public function testQuotes()
  {
    $class = $this->_getTester();
    $string = 'Test for a quoted \'string\' in the middle and the \'end\'';
    $this->assertEquals($string, $class->_('test_quoted_string_middle_qlhd63', $string));
  }
}
