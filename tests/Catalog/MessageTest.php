<?php

namespace Tests\Catalog;

use Packaged\I18n\Catalog\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
  public function testInvalidOption()
  {
    $this->expectExceptionMessage("Message Options must be either string or array");
    Message::create(null, new \stdClass());
  }
}
