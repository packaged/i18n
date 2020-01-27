<?php
namespace Packaged\I18n\Translators;

class TranslationLogger implements Translator
{
  protected $_translator;
  protected $_translations;

  const KEY_USAGES = 'usages';
  const KEY_CHOICES = 'choices';
  const KEY_REPLACEMENTS = 'replacements';

  public function __construct(Translator $translator)
  {
    $this->_translator = $translator;
  }

  public function _($msgId, $default, array $replacements = null, $choice = null): string
  {
    if(!isset($this->_translations[$msgId]))
    {
      $this->_translations[$msgId] = [
        static::KEY_USAGES       => 0,
        static::KEY_CHOICES      => [],
        static::KEY_REPLACEMENTS => false,
      ];
    }

    $this->_translations[$msgId][static::KEY_USAGES]++;
    if(!empty($replacements))
    {
      $this->_translations[$msgId][static::KEY_REPLACEMENTS] = true;
    }
    if($choice !== null)
    {
      $this->_translations[$msgId][static::KEY_CHOICES][] = $choice;
    }

    return $this->_translator->_($msgId, $default, $replacements, $choice);
  }

  public function getTranslations()
  {
    return $this->_translations;
  }
}
