<?php
namespace Packaged\I18n\Tools\Gettext;

class PoFile
{
  protected $_language;

  /**
   * @var PoTranslation[]
   */
  protected $_translations = [];

  public function __construct($language = null)
  {
    $this->_language = $language;
  }

  public function addTranslation(PoTranslation ...$translations)
  {
    foreach($translations as $translation)
    {
      $this->_translations[$translation->getId()] = $translation;
    }
    return $this;
  }

  public function __toString()
  {
    $file = [];
    $file[] = 'msgid ""';
    $file[] = 'msgstr ""';
    $file[] = '"MIME-Version: 1.0\n"';
    $file[] = '"Content-Type: text/plain; charset=UTF-8\n"';
    $file[] = '"Content-Transfer-Encoding: 8bit\n"';
    if($this->_language)
    {
      $file[] = '"Language: ' . $this->_language . '\n"';
    }
    $file[] = '"X-Generator: Packaged I18n 1.0\n"';
    $file[] = '';

    foreach($this->_translations as $translation)
    {
      $file = array_merge($file, $translation->getContent());
    }

    return implode(PHP_EOL, $file);
  }

  public static function fromString(string $poContent): ?PoFile
  {
    $trans = new static();
    $translations = explode("\n\n", $poContent);
    $matches = [];
    if(preg_match('/\"Language: ([\w_]+).*\"/mi', $poContent, $matches))
    {
      $trans->_language = $matches[1];
    }
    foreach($translations as $translation)
    {
      $converted = PoTranslation::fromString(trim($translation));
      if($converted !== null)
      {
        $trans->addTranslation($converted);
      }
    }
    return $trans;
  }
}
