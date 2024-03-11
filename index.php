<?php

use Packaged\I18n\Translators\ReplacementsOnlyTranslator;
use Packaged\I18n\Translators\Translator;

require 'vendor/autoload.php';

$trans = new Translator(new ReplacementsOnlyTranslator());
$start = microtime(true);
for($i = 0; $i < 1000; $i++)
{
  $trans->text("Hello");
}
$end = microtime(true);
echo "Time: " . (($end - $start) * 1000) . " ms\n";

echo $trans->text("Hello");
echo PHP_EOL;
echo $trans->plural("Apple", "Apples", 2);
echo PHP_EOL;
echo $trans->simplural("Apple(s)", 2);
echo PHP_EOL;
echo $trans->_sp('apple_s_cc7b', 'Apple(s)', 2);
echo PHP_EOL;
