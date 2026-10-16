<?php
namespace Tests\Tools\Gettext;

use Packaged\I18n\Tools\Gettext\PoTranslation;
use PHPUnit\Framework\TestCase;

class PoTranslationTest extends TestCase
{
  public function testSimpleString()
  {
    $source = <<<TRANS
#. Subject
#: subject_c789
#, fuzzy
msgid "Subject"
msgstr "Objet"
TRANS;

    $translation = PoTranslation::fromString($source);

    self::assertEquals('Subject', $translation->getSingularSource());
    self::assertEquals('Objet', $translation->getSingularTranslation());
    self::assertEquals(['subject_c789'], $translation->getReferences());
    self::assertTrue($translation->hasFlag(PoTranslation::FLAG_FUZZY));
  }

  public function testEmptyLine()
  {
    $source = <<<TRANS
#. Keeping data safe and secure is essential to avoiding fraud. Our Privacy
#. tools help keep your personal data out of the wrong hands
#: keeping_data_safe_secure_esse_3fc248_130
#, fuzzy
msgid ""
"Keeping data safe and secure is essential to avoiding fraud. Our Privacy "
"tools help keep your personal data out of the wrong hands"
msgstr "La protection des données est essentielle pour éviter les fraudes. Nos outils de confidentialité vous aident à sécuriser vos données personnelles pour qu'elles ne se retrouvent pas entre de mauvaises mains"
TRANS;

    $translation = PoTranslation::fromString($source);

    self::assertEquals(
      "Keeping data safe and secure is essential to avoiding fraud. Our Privacy tools help keep your personal data out of the wrong hands",
      $translation->getSingularSource()
    );
    self::assertEquals(
      "La protection des données est essentielle pour éviter les fraudes. Nos outils de confidentialité vous aident à sécuriser vos données personnelles pour qu'elles ne se retrouvent pas entre de mauvaises mains",
      $translation->getSingularTranslation()
    );
    self::assertEquals(
      [
        'Keeping data safe and secure is essential to avoiding fraud. Our Privacy',
        'tools help keep your personal data out of the wrong hands',
      ],
      $translation->getExtractedComments()
    );
    self::assertEquals(['keeping_data_safe_secure_esse_3fc248_130'], $translation->getReferences());
    self::assertTrue($translation->hasFlag(PoTranslation::FLAG_FUZZY));
  }

  public function testWrappedMsgidAndMsgstr()
  {
    $source = <<<TRANS
# Kept the platform names untranslated.
#: compatible_with_chrome_edge_a1b2c3_101
#,  echo-lingua-processed echo-lingua-translation
msgid ""
"Compatible with Chrome, Edge, Safari, iOS{iosMinimum}, "
"Android{androidMinimum}, Windows, and macOS. Feature availability may vary "
"by platform and OS version."
msgstr ""
"Compatible avec Chrome, Edge, Safari, iOS{iosMinimum}, "
"Android{androidMinimum}, Windows et macOS. La disponibilité des fonctions "
"peut varier selon la plateforme."
TRANS;

    $translation = PoTranslation::fromString($source);

    self::assertEquals(
      'Compatible with Chrome, Edge, Safari, iOS{iosMinimum}, Android{androidMinimum}, Windows, and macOS. '
      . 'Feature availability may vary by platform and OS version.',
      $translation->getSingularSource()
    );
    self::assertEquals(
      'Compatible avec Chrome, Edge, Safari, iOS{iosMinimum}, Android{androidMinimum}, Windows et macOS. '
      . 'La disponibilité des fonctions peut varier selon la plateforme.',
      $translation->getSingularTranslation()
    );
    self::assertEquals(['Kept the platform names untranslated.'], $translation->getComments());
    self::assertEquals('compatible_with_chrome_edge_a1b2c3_101', $translation->getId());
    self::assertTrue($translation->hasFlag('echo-lingua-processed echo-lingua-translation'));
  }

  public function testEscapeRoundTrip()
  {
    $source = <<<'TRANS'
#: escaped_ref
msgid "Say \"hi\" to C:\\new\n"
"next line"
msgstr "Dis \"salut\" à C:\\new\nligne suivante"
TRANS;

    $translation = PoTranslation::fromString($source);

    self::assertEquals("Say \"hi\" to C:\\new\nnext line", $translation->getSingularSource());
    self::assertEquals("Dis \"salut\" à C:\\new\nligne suivante", $translation->getSingularTranslation());

    $written = (string)$translation;
    self::assertStringContainsString('msgid "Say \"hi\" to C:\\\\new\n"' . PHP_EOL . '"next line"', $written);
    self::assertStringContainsString('msgstr "Dis \"salut\" à C:\\\\new\n"' . PHP_EOL . '"ligne suivante"', $written);

    $reread = PoTranslation::fromString($written);
    self::assertEquals($translation->getSingularSource(), $reread->getSingularSource());
    self::assertEquals($translation->getSingularTranslation(), $reread->getSingularTranslation());
  }

  public function testAdditionalPluralTranslations()
  {
    $source = <<<TRANS
#: file_count
msgid "One file"
msgid_plural "Many files"
msgstr[0] "Jeden plik"
msgstr[1] "Pliki"
msgstr[2] "Plików"
TRANS;

    $translation = PoTranslation::fromString($source);

    self::assertEquals('Many files', $translation->getPluralSource());
    self::assertEquals(['2' => 'Plików'], $translation->getAdditionalPluralTranslations());
    self::assertStringContainsString('msgstr[2] "Plików"', (string)$translation);
  }
}
