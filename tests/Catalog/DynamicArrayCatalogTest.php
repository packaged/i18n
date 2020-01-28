<?php

namespace Tests\Catalog;

use Packaged\I18n\Catalog\DynamicArrayCatalog;
use Packaged\I18n\Catalog\Message;
use Packaged\I18n\Translators\Translator;
use PHPUnit\Framework\TestCase;

class DynamicArrayCatalogTest extends TestCase
{
  public function testAdd()
  {
    $catalog = DynamicArrayCatalog::fromFile(dirname(__DIR__) . '/Supporting/Catalog/ArrayCatalog/en.php');
    $this->assertInstanceOf(DynamicArrayCatalog::class, $catalog);

    $this->assertArrayHasKey('rand_mid', $catalog->getData());

    $this->assertNull($catalog->getMessage('unlocated'));
    $catalog->addMessage(
      'unlocated',
      'Translated Text',
      [Translator::DEFAULT_OPTION => 'Default Info', 0 => 'Nothing', 1 => 'One Thing']
    );

    $msg = $catalog->getMessage('unlocated');
    $this->assertInstanceOf(Message::class, $msg);

    $this->assertEquals('Translated Text', $msg->getText());
    $this->assertEquals('Default Info', $msg->getText('horse'));
    $this->assertEquals('Nothing', $msg->getText(0));
    $this->assertEquals('One Thing', $msg->getText(1));

    $this->assertArrayHasKey('rand_mid', $catalog->getData());
    $this->assertArrayHasKey('unlocated', $catalog->getData());
  }
}
