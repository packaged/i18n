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
}
