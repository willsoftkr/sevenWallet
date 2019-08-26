<?php
$sub_menu = "700000";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$cond_list = "select * from pinna_mining_cond";

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
      
        <th scope="col" >INDEX</th>
        <th scope="col" width="250">수당 이름</a></th>	  
		<th scope="col">계산시점</th>
		<th scope="col" colspan="5">실적조건1</th>
		<th scope="col" colspan="5">실적조건2</th>
        <th scope="col" colspan="4">본인직급</th>
		<th scope="col" colspan="3">하부직급</th>
        <th scope="col" colspan="2">메티럭스조건</th>
		<th scope="col" colspan="4">대수조건</th> 
		<th scope="col">극점도달</th>   
		<th scope="col" >계산할 수당</th>
		<th scope="col"> % </th>
		<th scope="col" rowspan="2">이월</th>
    </tr>

    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {   	
       
        $bg = 'bg'.($i%2);
    ?>

    <tr class="<?php echo $bg; ?>">
        
        <td class="td_chk"><?php echo $row['idx']?></td>
        
		 <td class="td_mbid"><a href="allowance_sett.php?what=u&amp;&amp;no=<?=$row['no'] ?>&allowance_name=<?=$row['allowance_name']?>&price_kind=<?=$row['price_cond']?>&immediate=<?=$row['immediate']?>&source=<?=$row['source']?>&source_in1=<?=$row['source_in1']?>&source_in2=<?=$row['source_in2']?>&source_cond1=<?=$row['source_cond1']?>&source_cond2=<?=$row['source_cond2']?>&mb_level_in1=<?=$row['mb_level_in1']?>&mb_level_in2=<?=$row['mb_level_in2']?>&mb_level_cond1=<?=$row['mb_level_cond1']?>&mb_level_cond2=<?=$row['mb_level_cond2']?>&partner_cnt=<?=$row['partner_cnt']?>&partner_cont=<?=$row['partner_cont']?>&history_in1=<?=$row['history_in1']?>&history_in2=<?=$row['history_in2']?>&history_cond1=<?=$row['history_cond1']?>&history_cond2=<?=$row['history_cond2']?>&per=<?=$row['per']?>&base_source=<?=$row['base_source']?>&edit_no=<?=$row['no']?>&andor=<?=$row['andor']?>&benefit_limit1=<?=$row['benefit_limit1']?>&sales_reset=<?=$row['sales_reset']?>&iwolyn=<?=$row['iwolyn']?>&max_reset1=<?=$row['max_reset1']?>&max_reset2=<?=$row['max_reset2']?>&source11=<?=$row['source11']?>&source_cond11=<?=$row['source_cond11']?>&source_cond12=<?=$row['source_cond12']?>&source_in11=<?=$row['source_in11']?>&source_in12=<?=$row['source_in12']?>&mb_level_in11=<?=$row['mb_level_in11']?>&mb_level_cond11=<?=$row['mb_level_cond11']?>&mb_level_cond12=<?=$row['mb_level_cond12']?>&cycle=<?=$row['cycle']?>&recom_kind=<?=$row['recom_kind']?>"><?=$row['allowance_name']?></a></td>

		<!-- <td class="td_mbid"><?php echo $row['chk']?></td>-->

		<td class="td_chk"><?php echo $row['immediate'] ?></td>
		<td rowspan=2 class="td_mbid"><?php echo ($row['source_in1']) ?></td>
		<td rowspan=2 class="td_chk"><?php echo ($row['source_cond1']) ?></td>
		<td rowspan=2 class="td_mbid"><?php echo ($row['source']) ?></td>
		<td rowspan=2 class="td_chk"><?php echo ($row['source_cond2']) ?></td>
		<td rowspan=2 class="td_mbid"><?php echo ($row['source_in2']) ?></td>


		<td rowspan=2 class="td_mbid"><?php echo ($row['source_in11']) ?></td>
		<td rowspan=2 class="td_chk"><?php echo ($row['source_cond11']) ?></td>
		<td rowspan=2 class="td_mbid"><?php echo ($row['source11']) ?></td>
		<td rowspan=2 class="td_chk"><?php echo ($row['source_cond12']) ?></td>
		<td rowspan=2 class="td_mbid"><?php echo ($row['source_in12']) ?></td>

		<td rowspan=2 class="td_mbid"><?php echo ($row['mb_level_in1']) ?></td>
		<td rowspan=2 class="td_chk"><?php echo ($row['mb_level_cond1']) ?></td>

		<td rowspan=2 class="td_chk"><?php echo ($row['mb_level_cond2']) ?></td>
		<td rowspan=2 class="td_mbid"><?php echo ($row['mb_level_in2']) ?></td>

		<td rowspan=2 class="td_mbid"><?php echo ($row['mb_level_in11']) ?></td>
		<td rowspan=2 class="td_chk"><?php echo ($row['mb_level_cond11']) ?></td>
		<td rowspan=2 class="td_mbid"><?php echo ($row['mb_level_cond12']) ?></td>

		

		<td rowspan=2 class="td_mbid"><?php echo ($row['partner_cnt']) ?></td>
		<td rowspan=2 class="td_mbid"><?php echo ($row['partner_cont']) ?></td>

		<td rowspan=2 class="td_mbid"><?php echo ($row['history_in1']) ?></td>
		<td rowspan=2 class="td_chk"><?php echo ($row['history_cond1']) ?></td>
		<td rowspan=2 class="td_chk"><?php echo ($row['history_cond2']) ?></td>
		<td rowspan=2 class="td_mbid"><?php echo ($row['history_in2']) ?></td>

		<td class="td_mbid"><?php echo ($row['max_reset1']) ?></td>

		<td class="td_name sv_use"><?php echo ($row['base_source']) ?></td>

		


		<td class="td_chk"><?php echo ($row['per']) ?></td>
		<td rowspan=2 class="td_chk"><?php echo ($row['iwolyn']) ?></td>
		

    </tr>



    </tbody>
    </table>
</div>

?>