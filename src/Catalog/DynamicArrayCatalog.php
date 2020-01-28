<?php
namespace Packaged\I18n\Catalog;

class DynamicArrayCatalog extends ArrayCatalog
{
  public function getData()
  {
    return $this->_data;
  }

  public function addMessage($messageId, $text, array $options = [])
  {
    $this->_data[$messageId] = [
      self::KEY_TEXT    => $text,
      self::KEY_OPTIONS => $options,
    ];
    return $this;
  }
}
