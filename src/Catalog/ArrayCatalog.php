<?php
namespace Packaged\I18n\Catalog;

use Packaged\I18n\Translators\Translator;

class ArrayCatalog implements MessageCatalog
{
  const KEY_TEXT = 'msgstr';
  const KEY_OPTIONS = 'msgopt';

  protected $_data;

  /**
   * ArrayCatalog constructor.
   *
   * @param $_data
   */
  public function __construct($_data)
  {
    $this->_data = $_data;
  }

  public static function fromFile($filePath)
  {
    $cat = new static(include($filePath));
    return $cat;
  }

  public function getMessage($messageId): ?Message
  {
    if(isset($this->_data[$messageId]))
    {
      $text = $this->_data[$messageId][self::KEY_TEXT] ?? null;
      return Message::create(
        $text,
        $this->_data[$messageId][self::KEY_OPTIONS] ?? [Translator::DEFAULT_OPTION => $text]
      );
    }
    return null;
  }
}
