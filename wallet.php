<?
require 'vendor/autoload.php';

use neto737\BitGoSDK\BitGoSDK;
use neto737\BitGoSDK\Enum\CurrencyCode;

$bitgo = new BitGoSDK('YOUR_API_KEY_HERE', CurrencyCode::BITCOIN, false);

$bitgo->walletId = 'YOUR_WALLET_ID_HERE';

$createAddress = $bitgo->createWalletAddress();
?>