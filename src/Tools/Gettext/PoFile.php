<?php
namespace Packaged\I18n\Tools\Gettext;

class PoFile
{
  protected $_language;
  protected $_headers = [
    'MIME-Version'              => '1.0',
    'Content-Type'              => 'text/plain; charset=UTF-8',
    'Content-Transfer-Encoding' => '8bit',
    'X-Generator'               => 'Packaged I18n 1.0',
  ];

  /**
   * @var PoTranslation[]
   */
  protected $_translations = [];

  public function __construct($language = null)
  {
    $this->_setLanguage($language);
  }

  public function getHeaders()
  {
    return $this->_headers;
  }

  protected function _setLanguage($lang)
  {
    $this->_language = $lang;
    $this->_headers['Language'] = $this->_language;
    return $this;
  }

  public function getLanguage()
  {
    return $this->_language;
  }

  public function addTranslation(PoTranslation ...$translations)
  {
    foreach($translations as $translation)
    {
      $this->_translations[$translation->getId()] = $translation;
    }
    return $this;
  }

  public function getTranslation($id): ?PoTranslation
  {
    return $this->_translations[$id] ?? null;
  }

  public function getTranslations()
  {
    return $this->_translations;
  }

  public function __toString()
  {
    $file = [];
    $file[] = 'msgid ""';
    $file[] = 'msgstr ""';

    foreach($this->_headers as $hk => $hv)
    {
      $file[] = '"' . $hk . ': ' . $hv . '\n"';
    }

    $file[] = '';

    foreach($this->_translations as $translation)
    {
      $file = array_merge($file, [''], $translation->getContent());
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
      if(substr($translation, 0, 18) == 'msgid ""' . "\n" . 'msgstr ""')
      {
        $trans->_headers = [];
        $headers = explode("\n", substr($translation, 18));
        foreach($headers as $header)
        {
          $header = trim($header, '"');
          [$hk, $hv] = explode(':', substr($header, -2) == '\n' ? substr($header, 0, -2) : $header, 2);
          $trans->_headers[$hk] = trim($hv);
        }
        $trans->_headers = array_filter($trans->_headers);
      }
      else
      {
        $converted = PoTranslation::fromString(trim($translation));
        if($converted !== null)
        {
          $trans->addTranslation($converted);
        }
      }
    }
    return $trans;
  }
}
