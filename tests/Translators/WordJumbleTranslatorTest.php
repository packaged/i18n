<?php

namespace Tests\Translators;

use Packaged\I18n\Translators\WordJumbleTranslator;
use PHPUnit\Framework\TestCase;
use Tests\Supporting\TranslatedTextTestClass;

class WordJumbleTranslatorTest extends TestCase
{
  protected function _getTester()
  {
    return $class = new TranslatedTextTestClass(new WordJumbleTranslator(WordJumbleTranslator::STYLE_RSORT));
  }

  public function testShuffle()
  {
    $class = $this->_getTester();
    $class->setTranslator(new WordJumbleTranslator(WordJumbleTranslator::STYLE_SHUFFLE));
    $this->assertNotEquals("abcdefghiklmnopqrstvxyz", $class->_t("abcdefghiklmnopqrstvxyz"));
  }

  public function testRsort()
  {
    $class = $this->_getTester();
    $class->setTranslator(new WordJumbleTranslator(WordJumbleTranslator::STYLE_RSORT));
    $this->assertEquals("ayxvtsrqponmlkihgfedcbz", $class->_t("abcdefghiklmnopqrstvxyz"));
  }

  public function testSort()
  {
    $class = $this->_getTester();
    $class->setTranslator(new WordJumbleTranslator(WordJumbleTranslator::STYLE_SORT));
    $this->assertEquals("zbcdefghiklmnopqrstvxya", $class->_t("zyxvtsrqponmlkihgfedcba"));
  }

  public function test_p()
  {
    $class = $this->_getTester();
    $msg = 'You hvae {fileCount} flie';
    $msg2 = 'You hvae {fileCount} flies';
    $this->assertEquals('You hvae 0 flies', $class->_p($msg, $msg2, 0, ['fileCount' => 0]));
    $this->assertEquals('You hvae 1 flie', $class->_p($msg, $msg2, 1, ['fileCount' => 1]));
    $this->assertEquals('You hvae 2 flies', $class->_p($msg, $msg2, 2, ['fileCount' => 2]));
  }

  public function test_sp()
  {
    $class = $this->_getTester();
    $msg = 'You hvae {fileCount} flie(s)';
    $this->assertEquals('You hvae 0 flies', $class->_sp($msg, 0, ['fileCount' => 0]));
    $this->assertEquals('You hvae 1 flie', $class->_sp($msg, 1, ['fileCount' => 1]));
    $this->assertEquals('You hvae 2 flies', $class->_sp($msg, 2, ['fileCount' => 2]));
    $this->assertEquals('You hvae 3 flies', $class->_sp($msg, 3, ['fileCount' => 3]));
  }

  public function test_t()
  {
    $class = $this->_getTester();
    $this->assertEquals('Hlleo John', $class->_t('Hello {name}', ['name' => 'John']));
  }
}
