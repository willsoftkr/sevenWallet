<?
$guid="GUID_HERE";
$firstpassword="PASSWORD_HERE";
$secondpassword="PASSWORD_HERE";
$amounta = "10000000";
$amountb = "400000";
$addressa = "1A8JiWcwvpY7tAopUkSnGuEYHmzGYfZPiq";
$addressb = "1ExD2je6UNxL5oSu6iPUhn9Ta7UrN8bjBy";
$recipients = urlencode('{
                  "'.$addressa.'": '.$amounta.',
                  "'.$addressb.'": '.$amountb.'
               }');

$json_url = "http://localhost:3000/merchant/$guid/sendmany?password=$firstpassword&second_password=$secondpassword&recipients=$recipients";
$json_data = file_get_contents($json_url);
$json_feed = json_decode($json_data);

$message = $json_feed->message;
$txid = $json_feed->tx_hash;
?>