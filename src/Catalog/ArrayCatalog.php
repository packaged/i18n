<?php
namespace Packaged\I18n\Catalog;

class ArrayCatalog implements MessageCatalog
{
  protected array $_data;

  /**
   * ArrayCatalog constructor.
   *
   * @param array $_data
   */
  public function __construct(array $_data)
  {
    $this->_data = $_data;
  }

  public static function fromFile(string $filePath): self
  {
    return new static(include($filePath));
  }

  public function getMessage($messageId): ?Message
  {
    if(isset($this->_data[$messageId]))
    {
      if(!is_array($this->_data[$messageId]))
      {
        $this->_data[$messageId] = [Message::DEFAULT_OPTION => $this->_data[$messageId]];
      }
      return new Message($this->_data[$messageId]);
    }
    return null;
  }
}
