<?php
include_once('./_common.php');
$mb_no = $member['mb_no'];

if($_GET['input_id']){

	$sql = "select * from (
		select  mb_no,
				mb_id,
				mb_recommend_no,
				mb_recommend,
				mb_name,
				depth
		from    (select * from g5_member
				 order by mb_recommend_no, mb_no) products_sorted,
				(select @pv := '$member[mb_no]') initialisation
		where   find_in_set(mb_recommend_no, @pv) > 0
		and     @pv := concat(@pv, ',', mb_no)
	) a where a.mb_id like '%$_GET[keyword]%'";

	header('Content-Type: application/json');

	$sth = sql_query($sql);
	$rows = array();
	while($r = mysqli_fetch_assoc($sth)) {
		$rows[] = $r;
	}
	print json_encode($rows);
}else{



$sql = "
select * from (
	select a.*, 
		ifnull(d.cnt, 0) as cnt,
		ifnull(noo.noo, 0) as noo, 
		ifnull(thirty.thirty, 0) as thirty,
		ifnull(rk.is_rise, '-') as is_rise,
		rk.rday
	from (
		select  
			mb_no,
			mb_id,
			ifnull(mb_recommend_no, 0) as mb_recommend_no,
			mb_recommend,
			depth,
			mb_open_date as enrolled,
			mb_recommend as sponsor,
			mb_brecommend as placement,
			substring(mb_bre_time,1,10) as placed_binary,
			mb_level
	from    g5_member c where mb_no = '{$mb_no}')a 
	left outer join (
		select mb_recommend, count(*) as cnt from g5_member  group by mb_recommend
	) d on a.mb_id = d.mb_recommend and d.cnt > 0
	left outer join (
		select * from noo2 where day = (select max(day) from noo2)
	) noo on a.mb_id = noo.mb_id
	left outer join (
		select * from thirty2 where day = (select max(day) from thirty2)
	) thirty on a.mb_id = thirty.mb_id 
	left outer join (
		select a.mb_id, if(old_level < rank, 1, 0) as is_rise, rday from rank a
			inner join (select mb_id, max(rank_day) as rday from rank 
		group by mb_id) b on a.mb_id = b.mb_id and a.rank_day = b.rday
	) rk on a.mb_id = rk.mb_id
	union all
	select a.*, 
		ifnull(d.cnt, 0) as cnt,
		ifnull(noo.noo, 0) as noo, 
		ifnull(thirty.thirty, 0) as thirty,
		ifnull(rk.is_rise, '-') as is_rise,
		rk.rday
	from (
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
			mb_level
		from    (select * from g5_member
				order by mb_recommend_no, mb_no) products_sorted,
				(select @pv := '{$mb_no}') initialisation
		where   find_in_set(mb_recommend_no, @pv) > 0
		and     @pv := concat(@pv, ',', mb_no)
	) a 
	left outer join (
		select mb_recommend, count(*) as cnt from g5_member  group by mb_recommend
	) d on a.mb_id = d.mb_recommend and d.cnt > 0
	left outer join (
		select * from noo2 where day = (select max(day) from noo2)
	) noo on a.mb_id = noo.mb_id
	left outer join (
		select * from thirty2 where day = (select max(day) from thirty2)
	) thirty on a.mb_id = thirty.mb_id 
	left outer join (
		select a.mb_id, if(old_level < rank, 1, 0) as is_rise, rday from rank a
			inner join (select mb_id, max(rank_day) as rday from rank 
		group by mb_id) b on a.mb_id = b.mb_id and a.rank_day = b.rday
	) rk on a.mb_id = rk.mb_id
	where a.depth < (select  depth + 5 from g5_member c where mb_no = '{$mb_no}')
) a 
";
// 쿼리를 가져올때 반드시 root 가 제일 상단에 있어야함. 그래서 order by mb_id 를 하면 안됨.

header('Content-Type: application/json');
//echo $sql;
$sth = sql_query($sql);
$rows = array();
while($r = mysqli_fetch_assoc($sth)) {
	$rows[] = $r;
}

print json_encode($rows);
}
?>
