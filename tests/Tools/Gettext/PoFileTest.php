<?php
namespace Tests\Tools\Gettext;

use Packaged\I18n\Tools\Gettext\PoFile;
use PHPUnit\Framework\TestCase;

class PoFileTest extends TestCase
{

  public function testCorrectLineSpacing()
  {
    $sourceVariations = [
      <<<TRANS
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
TRANS,
      <<<TRANS
#: translation_1_7c7c
#, fuzzy1
msgid "Translation 1"
msgstr "Translation 1"

#: translation_2_1410
#, fuzzy2
msgid "Translation 2"
msgstr "Translation 2"
TRANS,
      <<<TRANS
#, fuzzy1
msgid "Translation 1"
msgstr "Translation 1"

#, fuzzy2
msgid "Translation 2"
msgstr "Translation 2"
TRANS,
      <<<TRANS
msgid "Translation 1"
msgstr "Translation 1"

msgid "Translation 2"
msgstr "Translation 2"
TRANS,
    ];

    foreach($sourceVariations as $variation)
    {
      $translation = PoFile::fromString($variation);
      $translations = $translation->getTranslations();

      self::assertCount(2, $translations);
      self::assertEquals('Translation 1', $translations['translation_1_7c7c']->getSingularTranslation());
      self::assertEquals('Translation 2', $translations['translation_2_1410']->getSingularTranslation());
    }
  }

  public function testIncorrectLineSpacing()
  {
    $sourceVariations = [
      <<<TRANS
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
TRANS,
      <<<TRANS
#: translation_1_7c7c
#, fuzzy1
msgid "Translation 1"
msgstr "Translation 1"
#: translation_2_1410
#, fuzzy2
msgid "Translation 2"
msgstr "Translation 2"
TRANS,
      <<<TRANS
#, fuzzy1
msgid "Translation 1"
msgstr "Translation 1"
#, fuzzy2
msgid "Translation 2"
msgstr "Translation 2"
TRANS,
      <<<TRANS
msgid "Translation 1"
msgstr "Translation 1"
msgid "Translation 2"
msgstr "Translation 2"
TRANS,
    ];

    foreach($sourceVariations as $variation)
    {
      $translation = PoFile::fromString($variation);
      $translations = $translation->getTranslations();

      self::assertCount(2, $translations);
      self::assertEquals('Translation 1', $translations['translation_1_7c7c']->getSingularTranslation());
      self::assertEquals('Translation 2', $translations['translation_2_1410']->getSingularTranslation());
    }
  }

  public function testPluralForms()
  {
    $translation = new PoFile('en', 'nplurals=2; plural=(n != 1);');
    $translations = $translation->getTranslations();

    self::assertCount(0, $translations);
    self::assertEquals('en', $translation->getLanguage());
    self::assertEquals('nplurals=2; plural=(n != 1);', $translation->getHeaders()['Plural-Forms']);

    $outFile = (string)$translation;
    self::assertStringContainsString('Plural-Forms: nplurals=2; plural=(n != 1);\n', $outFile);
  }
}
