<?php
namespace Tests\Tools\Gettext;

use Packaged\I18n\Tools\Gettext\PoFile;
use PHPUnit\Framework\TestCase;

class PoFileTest extends TestCase
{

  public function testCorrectLineSpacing()
  {
    $source = <<<TRANS
#. Subject
#: subject_c789
#, fuzzy
msgid "Subject"
msgstr ""

#. Subject2
#: subject_c7892
#, fuzzy2
msgid "Subject2"
msgstr "Objet2"
TRANS;

    $translation = PoFile::fromString($source);

    self::assertCount(2, $translation->getTranslations());
  }

  public function testIncorrectLineSpacing()
  {
    $source = <<<TRANS
#. Subject
#: subject_c789
#, fuzzy
msgid "Subject"
msgstr "Objet"
#. Subject2
#: subject_c7892
#, fuzzy2
msgid "Subject2"
msgstr "Objet2"
TRANS;

    $translation = PoFile::fromString($source);

    self::assertCount(2, $translation->getTranslations());
  }
}
