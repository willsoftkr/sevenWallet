<?php
	include_once('./_common.php');

	$yyyymm = str_replace('-','',substr($_GET['startDate'], 0,-2));

	$days = substr($_GET['endDate'], -2) - substr($_GET['startDate'], -2) + 1;
	
	$sql = "select A.dt, DATE_FORMAT(STR_TO_DATE(A.dt,'%Y%m%d'), '%b %d') as day, ifnull(B.cnt,0) as newinteam,
			ifnull(C.benefit,0) as benefit, ifnull(D.today,0) as sale 
		from (
			select concat('".$yyyymm."', LPAD(@rownum,2,'0')) as dt, @rownum:=@rownum-1 as no from soodang_pay
			,(select @rownum:=".$days.") TMP
			where @rownum > 0
		) A left outer join (
			select mb_open_date AS dt, COUNT(*) AS cnt from (
				select  
					mb_no,
					mb_id,
					mb_recommend_no,
					mb_recommend,
					depth,
					mb_open_date as enrolled,
					mb_recommend as sponsor,
					mb_brecommend as placement,
					substring(mb_bre_time,1,10) as placed_binary,
					mb_level,
					mb_open_date
					
				from    (select * from g5_member
						order by mb_recommend_no, mb_no) products_sorted,
						(select @pv := '{$member['mb_no']}') initialisation
				where   find_in_set(mb_recommend_no, @pv) > 0
				and		@pv := concat(@pv, ',', mb_no)
			) a where mb_open_date between '".$_GET['startDate']."' AND '".$_GET['endDate']."'
			group by mb_open_date
		) B ON A.dt = B.dt
		left outer join (
			SELECT DATE_FORMAT(day, '%Y%m%d') as dt, round(sum(benefit),8) as benefit 
				FROM soodang_pay 
			WHERE mb_id = '{$member['mb_id']}' and day between '".$_GET['startDate']." 00:00:00' AND '".$_GET['endDate']." 23:59:59' and allowance_name not in ( 'mining payout (ETH)', 'mining payout (BTC)' )
				GROUP by DATE_FORMAT(day, '%Y%m%d')
		) C ON A.dt = C.dt
		left outer join (
			select replace(day,'-','') as dt, (today * cost.btc_cost) as today from today2, (select btc_cost from coin_cost) cost 
				where day between '".$_GET['startDate']."' AND '".$_GET['endDate']."' 
					and mb_id = '{$member['mb_id']}' 
		) D ON A.dt = D.dt 
	";

	//  print $sql;

	$sth = sql_query($sql);
	$rows = array();
	while($r = mysqli_fetch_assoc($sth)) {
		$rows[] = $r;
	}

	$obj = new stdClass();
	$obj->list = $rows;
	$obj->sql = $sql;
	
	print json_encode($obj);

?>