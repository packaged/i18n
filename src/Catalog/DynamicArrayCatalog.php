<?php
namespace Packaged\I18n\Catalog;

class DynamicArrayCatalog extends ArrayCatalog
{
  public function getData()
  {
    return $this->_data;
  }

  public function addMessage($messageId, array $options = [])
  {
    $this->_data[$messageId] = $options;
    return $this;
  }
}
