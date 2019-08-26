<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.G5_SHOP_SKIN_URL.'/style.css">', 0);
?>
<style type="text/css">
.list_BxType {padding-bottom:30px;border-bottom:solid 1px #ddd;}
.list_BxType > ul > li {position:relative;float:left;width:282px;margin-right:24px;margin-bottom:24px;}
.list_BxType > ul > li .thumb {width:280px;height:280px;background-color:#f9f9f9;border:solid 1px #ddd;}
.list_BxType > ul > li .cont {padding:15px 5px;font-size:13px;line-height:20px;}
.list_BxType > ul > li .cont .sbj {line-height:20px;height:40px;overflow:hidden;margin-bottom:5px;}
.list_BxType > ul > li .cont .sbj a {color:#000;font-size:13px;font-family:"nngdb";}
.list_BxType > ul > li .cont .price {color:#000;font-family:"nngdb";font-size:14px;}
.list_BxType > ul > li .cont .price strike {color:#999;font-size:13px;}
.list_BxType > ul > li:nth-child(4n+0) {margin-right:0;}
</style>
<div class="list_BxType">
	<ul>
	<?
		for ($i=1; $row=sql_fetch_array($result); $i++) {
	?>
		<li>
			<div class="thumb">
			<?
				if ($this->href) {
					echo "<div class=\"sct_img\"><a href=\"{$this->href}{$row['it_id']}\" class=\"sct_a\">\n";
				}

				if ($this->view_it_img) {
					echo get_it_image($row['it_id'], $this->img_width, $this->img_height, '', '', stripslashes($row['it_name']))."\n";
				}

				if ($this->href) {
					echo "</a></div>\n";
				}
			?>
			</div><!-- // thumb -->
			<div class="cont">
				<div class="sbj">
				<?
					if ($this->href) {
						echo "<div class=\"sct_txt\"><a href=\"{$this->href}{$row['it_id']}\" class=\"sct_a\">\n";
					}

					if ($this->view_it_name) {
						echo stripslashes($row['it_name'])."\n";
					}

					if ($this->href) {
						echo "</a></div>\n";
					}
				?>
				</div><!-- // tx -->
				<div class="price">
				<?
					if ($this->view_it_cust_price || $this->view_it_price) {
						if ($this->view_it_cust_price && $row['it_cust_price']) {
							echo "<strike>".display_price($row['it_cust_price'])."</strike>\n";
						}
						if ($this->view_it_price) {
							echo "판매가 ".display_price(get_price($row), $row['it_tel_inq'])."\n";
						}
					}
				?>
				</div><!-- // price -->

			</div><!-- // cont -->

		</li>
	<?

		}
	?>
	</ul>
	<p class="clr"></p>
</div><!-- // list_BxType -->