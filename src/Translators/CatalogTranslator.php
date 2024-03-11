<?php
namespace Packaged\I18n\Translators;

use Packaged\I18n\Catalog\Message;
use Packaged\I18n\Catalog\MessageCatalog;

class CatalogTranslator extends AbstractTranslator
{
  /**
   * @var MessageCatalog
   */
  protected MessageCatalog $_catalog;

  public function __construct(MessageCatalog $catalog)
  {
    $this->_catalog = $catalog;
  }

  public function _($msgId, $default, array $replacements = null, $choice = null): string
  {
    $msg = $this->_catalog->getMessage($msgId);
    if($msg === null)
    {
      $msg = Message::fromDefault($default);
    }
    return $this->_applyReplacements($msg->getText($choice), $replacements);
  }
}
