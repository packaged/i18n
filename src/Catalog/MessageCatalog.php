<?php
namespace Packaged\I18n\Catalog;

interface MessageCatalog
{
  public function getMessage($messageId): ?Message;
}
