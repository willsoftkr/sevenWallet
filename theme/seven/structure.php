<?
	include_once('./_common.php');
	include_once(G5_THEME_PATH.'/_include/gnb.php'); 
	//print_r($member);
	 login_check($member['mb_id']);
?>



<link rel="stylesheet" href="<?=G5_THEME_URL?>/_common/css/level_structure.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.11/lodash.min.js"></script>
<script>
/* ETBC
	var depthMap = {
		0 : 'lvl-one dl_1depth',
		1 : 'lvl-two dl_2depth',
		2 : 'lvl-three dl_3depth',
		3 : 'lvl-four dl_4depth',
		4 : 'lvl-five dl_5depth',
		5 : 'lvl-six dl_6depth',
		6 : 'lvl-seven dl_7depth',
		7 : 'lvl-eight dl_8depth',
		8 : 'lvl-nine dl_9depth',
		9 : 'lvl-ten dl_10depth'
	};
*/

var depthMap = {
		0 : 'dl_1depth',
		1 : 'dl_2depth',
		2 : 'dl_3depth',
		3 : 'dl_4depth',
		4 : 'dl_5depth',
		5 : 'dl_6depth',
		6 : 'dl_7depth'
	};

var gradeMap = {
		0 : 'gr_0',
		1 : 'gr_1',
		2 : 'gr_2',
		3 : 'gr_3',
	};

	var $selected;
	var mb_no = '<?=$member['mb_no']?>';
	//var xhr;

	$(function() {
		// 상세보기 

		$(document).on('click','.lvl' ,function(e) {
			$(this).toggleClass('lvl-is-open');
			$selected = $(this).next();
			if($selected.css('max-height') != '0px' ){
				$selected.css('max-height','0px');
			}else{
				$selected.css('max-height', $selected.prop('scrollHeight') + 'px');
			}
			// console.log($(this).attr('mb_no'));
			if($(this).hasClass('lvl-is-open')){
				$.get( "/bbs/level_structure_upgraded.mem.php", {
					mb_no: $(this).attr('mb_no')
				}).done(function( data ) {
					if(data){
						$selected.find('.name').text(data.mb_id);
						$selected.find('.sponsor').text(data.mb_recommend);
						$selected.find('.enroll').text(data.enrolled);
						if(data.mb_level > 1 && data.mb_level < 9){
							$selected.find('.rank').text((data.mb_level -2) + ' Star');
						}
						$selected.find('.email').text(data.mb_email);
						$selected.find('.pool1').text(data.it_pool1);
						$selected.find('.pool2').text(data.it_pool2);
						$selected.find('.pool3').text(data.it_pool3);
						$selected.find('.gpu').text(data.it_gpu);
					}
				}).fail(function(e) {
					console.log( e );
				});
			}
		});

		 $(document).on('click','.lvl-username' ,function(e) {
			console.log($(this).text());
			//getLeg('<?=$member['mb_id']?>', $(this).text());
			//getList( $(this).text(),name);
			//e.stopPropagation();

			getList($(this).text(), 'name');
			//getLeg('<?=$member['mb_id']?>', $(this).text());
			$('.search_container').removeClass("active");

		 });

		$(document).on('click','.lvl > img' ,function(e) {
			var con = $(this).parents('.lvl-container');
			// con.attr('class').replace('lvl-container ','')
			var level = con.attr('class').replace('lvl-container ','');
			// console.log(level);
			if(con.hasClass('closed')){
				con.nextUntil( "." + level ).removeClass('closed').show();
				con.removeClass('closed');
			}else{
				con.nextUntil( "." + level ).hide();
				con.addClass('closed');
			}
			e.stopPropagation();
		});

		$(document).on('click','.go' ,function(e) {
			var search_mb_id = $(this).parent().parent().find('.lvl-username').text();
			getList(search_mb_id, 'name');
			//getLeg('<?=$member['mb_id']?>', $(this).attr('mb_id'));
			e.stopPropagation();
		});
		
		// 검색결과 클릭
		$(document).on('click','.mbId' ,function(e) {
			getList($(this).text(), 'name');
			//getLeg('<?=$member['mb_id']?>', $(this).text());
			$('.structure_search_container').removeClass("active");
		});

		// 엔터키
		$('#now_id').keydown(function (key) {
			if(key.keyCode == 13){
				key.preventDefault();
				//$('button.search-button').trigger('click');
				member_search();
			}
		});

		// 조직도 데이터 가져오기
		getList(Number(mb_no),'num');

	});


	function depthFirstTreeSort(arr, cmp) {
		
		function makeTree(arr) {
			var tree = {};
			for (var i = 0; i < arr.length; i++) {
				if (!tree[arr[i].mb_recommend_no]) tree[arr[i].mb_recommend_no] = [];
				tree[arr[i].mb_recommend_no].push(arr[i]);
			}
			return tree;
		}

	
		function depthFirstTraversal(tree, id, cmp, callback) {

			var children = tree[id];

			if (children) {
				children.sort(cmp);
				for (var i = 0; i < children.length; i++) {
					callback(children[i]);
					if(children[i].mb_no != mb_no){
							
							depthFirstTraversal(tree, children[i].mb_no, cmp, callback);
						}
					/*
					if(mb_no > 2){
						depthFirstTraversal(tree, children[i].mb_no, cmp, callback);
					}else{
						if(children[i].mb_no != mb_no){
							console.log(tree );
							depthFirstTraversal(tree, children[i].mb_no, cmp, callback);
						}
					}
					*/
				}
				
			}
		}
	
		var i = 0;
		var tree = makeTree(arr);
		depthFirstTraversal(tree, arr[0].mb_recommend_no, cmp, function(node) {
			arr[i++] = node;
		});
	}
		
	// function nameCmp(a, b) { return a.mb_id.localeCompare(b.mb_id); }
	nameCmp = function(a, b){ return a.mb_no < b.mb_no; }


	// 검색하는 부분
	function getMember(){
		var findemb_id = $("#now_id").val();

		getList( findemb_id, 'name' );
		//getLeg('<?=$member['mb_id']?>', mb_id);
	}
	
	function member_search(){
			if($("#now_id").val() == ""){
				//alert("Please enter a keyword.");
				commonModal('Notice','Please enter a keyword.',80);
				$("#now_id").focus();
				return;
			}
			
			$.get("/bbs/level_structure_upgraded.search.php", {
				keyword: $("#now_id").val()
			}).done(function( data ) {
				$('.structure_search_container').addClass("active");
				var vHtml = $('<div>');
				$.each(data, function( index, member ) {
					var line = $('<div>').append($('<strong>').addClass('mbId').html(member.mb_id));

					if(member.mb_name != ''){
						line.append('<br>');
						line.append( '(' + member.mb_name + ')');
					}else{
						line.css('line-height','50px');
					}
					vHtml.append(line);
				});
				$("#structure_search_result").html(vHtml.html());

				$(".structure_search_container .result_btn").on('click',function(e){
				//console.log(1);
				$('.search_container').removeClass("active");
		});
			}).fail(function(e) {
				console.log( e );
			});
		}


	function getList(member_no, type){

		$.get( "/bbs/level_structure_upgraded.list.php", {
			mb_no: member_no,
			type : type
		}).done(function( data ) {
			//tt = data;
			//console.log(data );
			var minObj = _.minBy(data, function(o) { return Number(o.depth); });
			
			_.forEach(data, function(member) {
				member.treelvl = member.depth - minObj.depth;
				member.gradelvl = member.grade;
			});
			
			depthFirstTreeSort(data, nameCmp);

			$('#total').text(data.length);

			var vHtml = $('<div>');
			$.each(data, function( index, member ) {
				
				var row = $('#dup .lvl-container').clone();
				
				row.addClass(depthMap[member.treelvl]);
				row.addClass(gradeMap[member.gradelvl]);

				row.find('.lvl-username').text(member.mb_id);
				
				//row.find('dt p').addClass('s_v'+(member.treelvl));
				//row.find('dt p').text("V"+ (member.treelvl));
				
				//row.find('dt p').addClass('s_v'+(7 -member.treelvl));
				//row.find('dt p').text("V"+ (7 -member.treelvl));

				/*
				row.find('.depth').text(member.treelvl + 1);

				// row.find('.ranked').text(member.is_rise);

				if(member.is_rise == '1'){
					row.find('.ranked').addClass("fa-long-arrow-alt-up").addClass("green").text('('+member.rday+')');
				}else if(member.is_rise == '0'){
					row.find('.ranked').addClass("fa-long-arrow-alt-down").addClass("red").text('('+member.rday+')');
				}else{
					row.find('.ranked').text('-');
				}

				row.find('.sale30').text("$ "+ numeral(member.thirty).format('0,0'));
				row.find('.saleAll').text("$ "+ numeral(member.noo).format('0,0'));
				row.find('.childCnt').text(member.cnt);
				row.find('.go').attr('mb_no',member.mb_no);
				row.find('.go').attr('mb_id',member.mb_id);

				if(member.mb_level < 9){
					row.find('.lvl img').prop('src','images/' + member.mb_level + 'eos.png');
				}
				row.find('.lvl').attr('mb_no',member.mb_no);
				*/
				vHtml.append(row);
			});
			
			$('#levelStructure').html(vHtml.html());
			$("html, body").animate({ scrollTop: 0 }, "fast");
			
			/*상세보기*/
			$('.accordion_wrap dl dd').css("display", "none");
			
			/*
			$('.accordion_wrap dt').click(function() {
				$(this).next().stop().slideToggle();
			});
			*/

		}).fail(function(e) {
			console.log( e );
		})
	}

	// 찾는 아이디에서 조상까지의 경로를 표시
	function getLeg(lastParent, findId){
		$.get("level_structure.leg.php", {
			lastParent : lastParent,
			findId : findId
		}).done(function( data ) {
			var reversed = data.reverse(); 
			//console.log(reversed);
			var vHtml = $('<div>');
			$.each(reversed, function( index, str ) {
				if(vHtml.html() == ''){
					vHtml.append($('<span>').addClass('mbId').text(str));
				}else{
					vHtml.append(" -> ").append($('<span>').addClass('mbId').text(str));
				}
			});
			$('.leg-view-container .gray').html(vHtml.html());
		}).fail(function(e) {
			console.log( e );
		});
	}

		

		
		
	</script>

		<section class="v_center structure_wrap">
			<p data-i18n='structure.데이터 크기로 인해 한번에 5대씩 화면에 나타납니다'>Due to the amount of data, only 5 steps are shown</p>
			<div class="btn_input_wrap">
				<input type="text" id="now_id" placeholder="Member Search" data-i18n='[placeholder]structure.회원찾기'/>
				
				<button type="button" class="btn wide blue" id="binary_search" data-i18n='검색' onclick="member_search();">Search</button>
			</div>
				<div class="structure_search_container">
					<div class="structure_search_result" id="structure_search_result">

					</div>
					<div class="result_btn">Close</div>
				</div>
			<div class="bin_top" data-i18n="structure.추천 계보" onclick="getList(<?=$member['mb_no']?>,'no');">Member Stack</div>
			
			<div class="main-container">
				<div id="levelStructure" class="accordion_wrap" ></div>
			</div>
	 
			<div style="display:none;" id="dup">
				<dl class="lvl-container" >
					<dt class="_lvl">
						<p class=""></p>
							<span  class="lvl-username">Dream123</span>
							<!--
							<div>
								<b>추천</b> : 4 &#47;
								<b>단계</b> : 0 &#47;
								<b>입금</b> : &#36;100,000 &#47;
								<b>산하매출</b> : &#36;20,000,000
							</div>
							-->
					</dt>
					<dd>
					<!--
						<div>
								<p>
									<span>이름 : </span>
									<strong></strong>
								</p>
								<p>
									<span>매출일 : </span>
									<strong>2018-05-09</strong>
								</p>
								<p>
									<span>추천인 : </span>
									<strong>moll123456</strong>
								</p>
							</div>
							<div>
								<p>
									<span>직급 : </span>
									<strong>V5</strong>
								</p>
								<p>
									<span>상태 : </span>
									<strong>Active</strong>
								</p>
								<p>
									<span>이메일 : </span>
									<strong>qwer123@gmail.com</strong>
								</p>
							</div>
							<a href="#" class="go">go</a>
					</dd>
					-->
					</div>			
				</dl>
		  </div>
	  <!--
		<div class="accordion_wrap">
			
				<dl>
					<dt>
						<p class="s_v5">V5</p>
						<strong>Dream123</strong>
						<div>
							<b>추천</b> : 4 &#47;
							<b>단계</b> : 0 &#47;
							<b>입금</b> : &#36;100,000 &#47;
							<b>산하매출</b> : &#36;20,000,000
						</div>
					</dt>
					<dd>
						<div>
							<p>
								<span>이름 : </span>
								<strong>Kang il</strong>
							</p>
							<p>
								<span>매출일 : </span>
								<strong>2018-05-09</strong>
							</p>
							<p>
								<span>추천인 : </span>
								<strong>moll123456</strong>
							</p>
						</div>
						<div>
							<p>
								<span>직급 : </span>
								<strong>V5</strong>
							</p>
							<p>
								<span>상태 : </span>
								<strong>Active</strong>
							</p>
							<p>
								<span>이메일 : </span>
								<strong>qwer123@gmail.com</strong>
							</p>
						</div>
						<a href="#">go</a>
					</dd>
				</dl>
				-->
				<!--
				<dl class="dl_2depth">
					<dt>
						<p class="s_v3">V3</p>
						<strong>Rose</strong>
						<div>
							<b>추천</b> : 4 &#47;
							<b>단계</b> : 1 &#47;
							<b>입금</b> : &#36;100,000 &#47;
							<b>산하매출</b> : &#36;20,000,000
						</div>
					</dt>
					<dd>
						<div>
							<p>
								<span>이름 : </span>
								<strong>Kang il</strong>
							</p>
							<p>
								<span>매출일 : </span>
								<strong>2018-05-09</strong>
							</p>
							<p>
								<span>추천인 : </span>
								<strong>moll123456</strong>
							</p>
						</div>
						<div>
							<p>
								<span>직급 : </span>
								<strong>V3</strong>
							</p>
							<p>
								<span>상태 : </span>
								<strong>Active</strong>
							</p>
							<p>
								<span>이메일 : </span>
								<strong>qwer123@gmail.com</strong>
							</p>
						</div>
						<a href="#">go</a>
					</dd>
				</dl>
				<dl class="dl_3depth">
					<dt>
						<p class="s_v3">V3</p>
						<strong>Hong</strong>
						<div>
							<b>추천</b> : 4 &#47;
							<b>단계</b> : 2 &#47;
							<b>입금</b> : &#36;100,000 &#47;
							<b>산하매출</b> : &#36;20,000,000
						</div>
					</dt>
					<dd>
						<div>
							<p>
								<span>이름 : </span>
								<strong>Kang il</strong>
							</p>
							<p>
								<span>매출일 : </span>
								<strong>2018-05-09</strong>
							</p>
							<p>
								<span>추천인 : </span>
								<strong>moll123456</strong>
							</p>
						</div>
						<div>
							<p>
								<span>직급 : </span>
								<strong>V3</strong>
							</p>
							<p>
								<span>상태 : </span>
								<strong>Active</strong>
							</p>
							<p>
								<span>이메일 : </span>
								<strong>qwer123@gmail.com</strong>
							</p>
						</div>
						<a href="#">go</a>
					</dd>
				</dl>
				<dl class="dl_4depth">
					<dt>
						<p>V0</p>
						<strong>img</strong>
						<div>
							<b>추천</b> : 4 &#47;
							<b>단계</b> : 3 &#47;
							<b>입금</b> : &#36;100,000 &#47;
							<b>산하매출</b> : &#36;20,000,000
						</div>
					</dt>
					<dd>
						<div>
							<p>
								<span>이름 : </span>
								<strong>Kang il</strong>
							</p>
							<p>
								<span>매출일 : </span>
								<strong>2018-05-09</strong>
							</p>
							<p>
								<span>추천인 : </span>
								<strong>moll123456</strong>
							</p>
						</div>
						<div>
							<p>
								<span>직급 : </span>
								<strong>V0</strong>
							</p>
							<p>
								<span>상태 : </span>
								<strong>Active</strong>
							</p>
							<p>
								<span>이메일 : </span>
								<strong>qwer123@gmail.com</strong>
							</p>
						</div>
						<a href="#">go</a>
					</dd>
				</dl>
				<dl class="dl_5depth">
					<dt>
						<p class="s_v1">V1</p>
						<strong>sing</strong>
						<div>
							<b>추천</b> : 4 &#47;
							<b>단계</b> : 4 &#47;
							<b>입금</b> : &#36;100,000 &#47;
							<b>산하매출</b> : &#36;20,000,000
						</div>
					</dt>
					<dd>
						<div>
							<p>
								<span>이름 : </span>
								<strong>Kang il</strong>
							</p>
							<p>
								<span>매출일 : </span>
								<strong>2018-05-09</strong>
							</p>
							<p>
								<span>추천인 : </span>
								<strong>moll123456</strong>
							</p>
						</div>
						<div>
							<p>
								<span>직급 : </span>
								<strong>V1</strong>
							</p>
							<p>
								<span>상태 : </span>
								<strong>Active</strong>
							</p>
							<p>
								<span>이메일 : </span>
								<strong>qwer123@gmail.com</strong>
							</p>
						</div>
						<a href="#">go</a>
					</dd>
				</dl>
				<dl class="dl_5depth">
					<dt>
						<p class="s_v2">V2</p>
						<strong>number</strong>
						<div>
							<b>추천</b> : 4 &#47;
							<b>단계</b> : 4 &#47;
							<b>입금</b> : &#36;100,000 &#47;
							<b>산하매출</b> : &#36;20,000,000
						</div>
					</dt>
					<dd>
						<div>
							<p>
								<span>이름 : </span>
								<strong>Kang il</strong>
							</p>
							<p>
								<span>매출일 : </span>
								<strong>2018-05-09</strong>
							</p>
							<p>
								<span>추천인 : </span>
								<strong>moll123456</strong>
							</p>
						</div>
						<div>
							<p>
								<span>직급 : </span>
								<strong>V2</strong>
							</p>
							<p>
								<span>상태 : </span>
								<strong>Active</strong>
							</p>
							<p>
								<span>이메일 : </span>
								<strong>qwer123@gmail.com</strong>
							</p>
						</div>
						<a href="#">go</a>
					</dd>
				</dl>
				<dl class="dl_4depth">
					<dt>
						<p class="s_v4">V4</p>
						<strong>img</strong>
						<div>
							<b>추천</b> : 4 &#47;
							<b>단계</b> : 3 &#47;
							<b>입금</b> : &#36;100,000 &#47;
							<b>산하매출</b> : &#36;20,000,000
						</div>
					</dt>
					<dd>
						<div>
							<p>
								<span>이름 : </span>
								<strong>Kang il</strong>
							</p>
							<p>
								<span>매출일 : </span>
								<strong>2018-05-09</strong>
							</p>
							<p>
								<span>추천인 : </span>
								<strong>moll123456</strong>
							</p>
						</div>
						<div>
							<p>
								<span>직급 : </span>
								<strong>V4</strong>
							</p>
							<p>
								<span>상태 : </span>
								<strong>Active</strong>
							</p>
							<p>
								<span>이메일 : </span>
								<strong>qwer123@gmail.com</strong>
							</p>
						</div>
						<a href="#">go</a>
					</dd>
				</dl>
				<dl class="dl_4depth">
					<dt>
						<p class="s_v7">V7</p>
						<strong>hyde</strong>
						<div>
							<b>추천</b> : 4 &#47;
							<b>단계</b> : 3 &#47;
							<b>입금</b> : &#36;100,000 &#47;
							<b>산하매출</b> : &#36;20,000,000
						</div>
					</dt>
					<dd>
						<div>
							<p>
								<span>이름 : </span>
								<strong>Kang il</strong>
							</p>
							<p>
								<span>매출일 : </span>
								<strong>2018-05-09</strong>
							</p>
							<p>
								<span>추천인 : </span>
								<strong>moll123456</strong>
							</p>
						</div>
						<div>
							<p>
								<span>직급 : </span>
								<strong>V7</strong>
							</p>
							<p>
								<span>상태 : </span>
								<strong>Active</strong>
							</p>
							<p>
								<span>이메일 : </span>
								<strong>qwer123@gmail.com</strong>
							</p>
						</div>
						<a href="#">go</a>
					</dd>
				</dl>
				<dl class="dl_2depth">
					<dt>
						<p class="s_v6">V6</p>
						<strong>Rose</strong>
						<div>
							<b>추천</b> : 4 &#47;
							<b>단계</b> : 1 &#47;
							<b>입금</b> : &#36;100,000 &#47;
							<b>산하매출</b> : &#36;20,000,000
						</div>
					</dt>
					<dd>
						<div>
							<p>
								<span>이름 : </span>
								<strong>Kang il</strong>
							</p>
							<p>
								<span>매출일 : </span>
								<strong>2018-05-09</strong>
							</p>
							<p>
								<span>추천인 : </span>
								<strong>moll123456</strong>
							</p>
						</div>
						<div>
							<p>
								<span>직급 : </span>
								<strong>V3</strong>
							</p>
							<p>
								<span>상태 : </span>
								<strong class="font_red">Inactive</strong>
							</p>
							<p>
								<span>이메일 : </span>
								<strong>qwer123@gmail.com</strong>
							</p>
						</div>
						<a href="#">go</a>
					</dd>
				</dl>
					
			</div>
			-->	
		</section>

		<div class="gnb_dim"></div>

	</section>



	<script>
		$(function() {
			$(".top_title h3").html("<img src='<?=G5_THEME_URL?>/_images/top_structure.png' alt='아이콘'><span data-i18n='structure.조직도 보기'> Level Structure</span>");
			$('#wrapper').css("background", "#fff");
		});
	</script>

<? include_once(G5_THEME_PATH.'/_include/tail.php'); ?>

