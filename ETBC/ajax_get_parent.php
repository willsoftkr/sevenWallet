<?php
$sub_menu = "600300";
include_once('./_common.php');
include_once('./inc.member.class.php');


//$m_id      = "0010000005";

if (!$m_id) $m_id      = "0010000378";

get_recommend_up($m_id);

?>