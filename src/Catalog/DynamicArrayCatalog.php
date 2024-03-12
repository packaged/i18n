<?php
namespace Packaged\I18n\Catalog;

class DynamicArrayCatalog extends ArrayCatalog
{
  /**
   * @var callable
   */
  protected $_translateCallback;

  public function getData(): array
  {
    return $this->_data;
  }

  public function addMessage(string $messageId, array $options = [])
  {
    $this->_data[$messageId] = $options;
    return $this;
  }

  public function asPhpFile(bool $withNewLines = false): string
  {
    $indent = $withNewLines ? '  ' : '';
    $implode = $withNewLines ? PHP_EOL : '';

    $content = ['<?php', PHP_EOL, 'return ['];

    $callback = $this->_translateCallback;

    foreach($this->getData() as $mid => $options)
    {
      $content[] = $indent . "'" . addcslashes(stripslashes($mid), "'") . "' => [";
      foreach($options as $optK => $text)
      {
        $useText = $callback ? $callback($text) : $text;
        $content[] = $indent . $indent
          . "'" . addcslashes(stripslashes($optK), "'") . "' => '" . addcslashes(stripslashes($useText), "'") . "',";
      }
      $content[] = $indent . '],';
    }
    $content[] = '];';
    return implode($implode, $content) . PHP_EOL;
  }

  /**
   * Process text through this callback when generating the php file
   *
   * @param callable $func
   *
   * @return $this
   */
  public function setTranslationCallback(callable $func): self
  {
    $this->_translateCallback = $func;
    return $this;
  }
}
