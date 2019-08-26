<?
include_once("./_common.php");

require './blockchain/vendor/autoload.php';
$Blockchain = new \Blockchain\Blockchain();
$Blockchain->setServiceUrl('http://localhost:3000');
?>
