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

  public function testWrappedHeaders()
  {
    $source = <<<'TRANS'
msgid ""
msgstr ""
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset=utf-8\n"
"Language: cs\n"
"Plural-Forms: nplurals=3; plural=(n==1 ? 0 : n%10>=2 && n%10<=4 && "
"(n%100<10 || n%100>=20) ? 1 : 2);\n"

#: subject_c789
msgid "Subject"
msgstr "Předmět"
TRANS;

    $file = PoFile::fromString($source);

    self::assertEquals('cs', $file->getLanguage());
    self::assertEquals(
      [
        'MIME-Version' => '1.0',
        'Content-Type' => 'text/plain; charset=utf-8',
        'Language'     => 'cs',
        'Plural-Forms' => 'nplurals=3; plural=(n==1 ? 0 : n%10>=2 && n%10<=4 && (n%100<10 || n%100>=20) ? 1 : 2);',
      ],
      $file->getHeaders()
    );
    self::assertCount(1, $file->getTranslations());
  }

  public function testEntriesStayWhole()
  {
    $source = <<<'TRANS'
msgid ""
msgstr ""
"Language: pl\n"

#: menu_open
msgctxt "menu"
msgid "Open"
msgstr "Otwórz"

#: dialog_open
msgctxt ""
"dialog "
"window"
msgid "Open"
msgstr "Otwórz okno"

#: file_count
msgid "One file"
msgid_plural "Many files"
msgstr[0] "Jeden plik"
msgstr[1] "Pliki"
msgstr[2] "Plików"

#. Say "hi"
#: say_hi
msgid "Hi"
msgstr "Cześć"
#: after_hi
msgid "Bye"
msgstr "Pa"
TRANS;

    $translations = PoFile::fromString($source)->getTranslations();

    self::assertEquals(['menu_open', 'dialog_open', 'file_count', 'say_hi', 'after_hi'], array_keys($translations));

    self::assertEquals('menu', $translations['menu_open']->getContextString());
    self::assertEquals('Open', $translations['menu_open']->getSingularSource());
    self::assertEquals('Otwórz', $translations['menu_open']->getSingularTranslation());

    self::assertEquals('dialog window', $translations['dialog_open']->getContextString());
    self::assertEquals('Otwórz okno', $translations['dialog_open']->getSingularTranslation());

    self::assertEquals('One file', $translations['file_count']->getSingularSource());
    self::assertEquals('Many files', $translations['file_count']->getPluralSource());
    self::assertEquals('Jeden plik', $translations['file_count']->getSingularTranslation());
    self::assertEquals('Pliki', $translations['file_count']->getPluralTranslation());
    self::assertEquals(['2' => 'Plików'], $translations['file_count']->getAdditionalPluralTranslations());

    self::assertEquals(['Say "hi"'], $translations['say_hi']->getExtractedComments());
    self::assertEquals('Hi', $translations['say_hi']->getSingularSource());
    self::assertEquals('Cześć', $translations['say_hi']->getSingularTranslation());

    self::assertEquals('Pa', $translations['after_hi']->getSingularTranslation());
  }

  public function testWrappedFileRoundTrip()
  {
    $source = <<<'TRANS'
msgid ""
msgstr ""
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset=utf-8\n"
"Content-Transfer-Encoding: 8bit\n"
"Language: pl\n"
"Plural-Forms: nplurals=3; plural=(n==1 ? 0 : n%10>=2 && n%10<=4 && "
"(n%100<10 || n%100>=20) ? 1 : 2);\n"

# Kept the platform names untranslated.
#: compatible_with_chrome_edge_a1b2c3_101
#. Compatible with Chrome, Edge, Safari, iOS{iosMinimum}, Android{androidMinimum}, Windows, and macOS. Feature availability may vary by platform and OS version.
#,  echo-lingua-processed echo-lingua-translation
msgid ""
"Compatible with Chrome, Edge, Safari, iOS{iosMinimum}, "
"Android{androidMinimum}, Windows, and macOS. Feature availability may vary "
"by platform and OS version."
msgstr ""
"Kompatybilne z Chrome, Edge, Safari, iOS{iosMinimum}, "
"Android{androidMinimum}, Windows i macOS. Dostępność funkcji może się "
"różnić w zależności od platformy i wersji systemu."

# Quoted the product name as in the source.
#: the_vault_d4e5f6_102
#. The "Vault" keeps your passwords safe
#,  echo-lingua-processed echo-lingua-translation
msgid "The \"Vault\" keeps your passwords safe"
msgstr "\"Sejf\" chroni Twoje hasła"
TRANS;

    $file = PoFile::fromString($source);
    $reparsed = PoFile::fromString((string)$file);

    $expected = [
      'compatible_with_chrome_edge_a1b2c3_101' => [
        'Compatible with Chrome, Edge, Safari, iOS{iosMinimum}, Android{androidMinimum}, Windows, and macOS. '
        . 'Feature availability may vary by platform and OS version.',
        'Kompatybilne z Chrome, Edge, Safari, iOS{iosMinimum}, Android{androidMinimum}, Windows i macOS. '
        . 'Dostępność funkcji może się różnić w zależności od platformy i wersji systemu.',
      ],
      'the_vault_d4e5f6_102'                   => [
        'The "Vault" keeps your passwords safe',
        '"Sejf" chroni Twoje hasła',
      ],
    ];

    foreach([$file, $reparsed] as $parsed)
    {
      $translations = $parsed->getTranslations();
      self::assertEquals(array_keys($expected), array_keys($translations));
      foreach($expected as $id => [$singularSource, $singularTranslation])
      {
        self::assertEquals($singularSource, $translations[$id]->getSingularSource());
        self::assertEquals($singularTranslation, $translations[$id]->getSingularTranslation());
        self::assertTrue($translations[$id]->hasFlag('echo-lingua-processed echo-lingua-translation'));
      }
      self::assertEquals(
        'nplurals=3; plural=(n==1 ? 0 : n%10>=2 && n%10<=4 && (n%100<10 || n%100>=20) ? 1 : 2);',
        $parsed->getHeaders()['Plural-Forms']
      );
      self::assertEquals('pl', $parsed->getLanguage());
    }

    self::assertEquals($file->getHeaders(), $reparsed->getHeaders());
    self::assertStringContainsString('msgid "The \"Vault\" keeps your passwords safe"', (string)$file);
  }
}
