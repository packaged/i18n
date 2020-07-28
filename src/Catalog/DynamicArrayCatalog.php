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

  public function asPhpFile(): string
  {
    $indent = $implode = '';

    $content = ['<?php', PHP_EOL, 'return ['];

    foreach($this->getData() as $mid => $options)
    {
      $content[] = $indent . "'" . addslashes($mid) . "' => [";
      foreach($options as $optK => $text)
      {
        $content[] = $indent . $indent . "'" . addslashes($optK) . "' => '" . addslashes($text) . "',";
      }
      $content[] = '],';
    }
    $content[] = '];';
    $content[] = '';
    return implode($implode, $content);
  }
}
