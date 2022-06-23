<?php
namespace Tests\Tools\Gettext;

use Packaged\I18n\Tools\Gettext\PoFile;
use PHPUnit\Framework\TestCase;

class PoFileTest extends TestCase
{

  public function testCorrectLineSpacing()
  {
    $source = <<<TRANS
#. Translation 1
#: translation_1_7c7c
#, fuzzy1
msgid "Translation 1"
msgstr "Translation 1"

#. Translation 2
#: translation_2_1410
#, fuzzy2
msgid "Translation 2"
msgstr "Translation 2"
TRANS;

    $translation = PoFile::fromString($source);

    self::assertCount(2, $translation->getTranslations());
    self::assertEquals('Translation 1', $translation->getTranslations()['translation_1_7c7c']->getSingularTranslation());
    self::assertEquals('Translation 2', $translation->getTranslations()['translation_2_1410']->getSingularTranslation());
  }

  public function testIncorrectLineSpacing()
  {
    $source = <<<TRANS
#. Translation 1
#: translation_1_7c7c
#, fuzzy1
msgid "Translation 1"
msgstr "Translation 1"
#. Translation 2
#: translation_2_1410
#, fuzzy2
msgid "Translation 2"
msgstr "Translation 2"
TRANS;

    $translation = PoFile::fromString($source);

    self::assertCount(2, $translation->getTranslations());
    self::assertEquals('Translation 1', $translation->getTranslations()['translation_1_7c7c']->getSingularTranslation());
    self::assertEquals('Translation 2', $translation->getTranslations()['translation_2_1410']->getSingularTranslation());
  }
}
