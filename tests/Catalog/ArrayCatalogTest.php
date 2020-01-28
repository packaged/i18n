<?php

namespace Tests\Catalog;

use Packaged\I18n\Catalog\ArrayCatalog;
use Packaged\I18n\Catalog\Message;
use PHPUnit\Framework\TestCase;

class ArrayCatalogTest extends TestCase
{
  public function testLoad()
  {
    $catalog = ArrayCatalog::fromFile(dirname(__DIR__) . '/Supporting/Catalog/ArrayCatalog/en.php');
    $this->assertInstanceOf(ArrayCatalog::class, $catalog);

    $this->assertNull($catalog->getMessage('missingid'));

    $this->assertEquals('Mr Lazy', $catalog->getMessage('lazy_translator')->getText());

    $msg = $catalog->getMessage('rand_mid');
    $this->assertInstanceOf(Message::class, $msg);

    $this->assertEquals('Default Text', $msg->getText());
    $this->assertEquals('Default Text', $msg->getText('horse'));
    $this->assertEquals('No Random Things', $msg->getText(0));
    $this->assertEquals('One Random Thing', $msg->getText(1));
    $this->assertEquals('Two Random Things', $msg->getText(2));
    $this->assertEquals('A Few Randoms', $msg->getText(3));
    $this->assertEquals('A Few Randoms', $msg->getText(4));
    $this->assertEquals('A Few Randoms', $msg->getText(5));
    $this->assertEquals('Lots More', $msg->getText(6));
    $this->assertEquals('Lots More', $msg->getText(7));
    $this->assertEquals('Male', $msg->getText('male'));
    $this->assertEquals('Female', $msg->getText('female'));
    $this->assertEquals('Pet', $msg->getText('cat'));
    $this->assertEquals('Pet', $msg->getText('dog'));
  }

}
