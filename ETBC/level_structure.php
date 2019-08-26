<?php
include_once('./_common.php');

?>
<!doctype html>
<html lang="ko">
<head>
	<?include_once('common_head.php')?>

	<link rel="stylesheet" href="css/style.css?v=201901225">
	<script>
		var depthMap = {
			0 : 'lvl-one',
			1 : 'lvl-two',
			2 : 'lvl-three',
			3 : 'lvl-four',
			4 : 'lvl-five',
			5 : 'lvl-six',
			6 : 'lvl-seven',
			7 : 'lvl-eight',
			8 : 'lvl-nine',
			9 : 'lvl-ten'
		};

		function depthFirstTreeSort(arr, cmp) {
			// Returns an object, where each key is a node number, and its value
			// is an array of child nodes.
			function makeTree(arr) {
				var tree = {};
				for (var i = 0; i < arr.length; i++) {
					if (!tree[arr[i].mb_recommend_no]) tree[arr[i].mb_recommend_no] = [];
					tree[arr[i].mb_recommend_no].push(arr[i]);
				}
				return tree;
			}

			// For each node in the tree, starting at the given id and proceeding
			// depth-first (pre-order), sort the child nodes based on cmp, and
			// call the callback with each child node.
			function depthFirstTraversal(tree, id, cmp, callback) {

				var children = tree[id];
				//console.log(children);
				if (children) {
					children.sort(cmp);
					for (var i = 0; i < children.length; i++) {
						//console.log(children)
						callback(children[i]);
						depthFirstTraversal(tree, children[i].mb_no, cmp, callback);
					}
				}
			}

			// Overwrite arr with the reordered result
			var i = 0;
			var tree = makeTree(arr);
			// console.log(tree);
			// var minkey = _.minBy(_.keys(tree), function(o) { return Number(o); });
			// console.log(minkey);
	
			depthFirstTraversal(tree, arr[0].mb_recommend_no, cmp, function(node) {
				arr[i++] = node;
			});
		}
		
		// function nameCmp(a, b) { return a.mb_id.localeCompare(b.mb_id); }
		function nameCmp(a, b) { return a.mb_no < b.mb_no; }
		
		var $selected;
		$(function() {
			// 상세보기 
      /*xx
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
					$.get( "level_structure_upgraded.mem.php", {
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

*/ /* 클릭 상세보기 제거 0618_moon */ 


			 $(document).on('click','.lvl-username' ,function(e) {
				console.log($(this).text());
			 	//getLeg('<?=$member[mb_id]?>', $(this).text());
				//getList( $(this).text(),name);
			 	//e.stopPropagation();

				getList($(this).text(), 'name');
				getLeg('<?=$member['mb_id']?>', $(this).text());
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
				getList($(this).attr('mb_no'));
				getLeg('<?=$member['mb_id']?>', $(this).attr('mb_id'));
				e.stopPropagation();
			});

			$(document).on('click','.mbId' ,function(e) {
				getList($(this).text(), 'name');
				getLeg('<?=$member['mb_id']?>', $(this).text());
				$('.search_container').removeClass("active");
			});

			$('button.search-button').click(function(){
				if($("#binary_seach").val() == ""){
					//alert("Please enter a keyword.");
					commonModal('Notice','Please enter a keyword.',80);
					$("#binary_seach").focus();
					return;
				}
				
				$.get( "level_structure_upgraded.search.php", {
					keyword: $("#binary_seach").val()
				}).done(function( data ) {
					$('.search_container').addClass("active");
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
					$("#search_result").html(vHtml.html());
				}).fail(function(e) {
					console.log( e );
				});
			});

			$(".search_container .result_btn").on('click',function(e){
				//console.log(1);
				$('.search_container').removeClass("active");
			});
			
			$('#binary_seach').on('keydown',function(e){
				if(e.which == 13) {
					e.preventDefault();
					$('button.search-button').trigger('click');
				}
			});

			// 조직도 데이터 가져오기
			getList(Number('<?=$member['mb_no']?>'));

		});

		var xhr;
		// 검색하는 부분
		function getMember(mb_id){
			$("#now_id").val(mb_id);

			getList($(this).attr('mb_no'));
			getLeg('<?=$member['mb_id']?>', mb_id);
		}

		function getList(member_no, type){
			$.get( "level_structure_upgraded.list.php", {
				mb_no: member_no,
				type : type
			}).done(function( data ) {
				//tt = data;
				var minObj = _.minBy(data, function(o) { return Number(o.depth); });
				//console.log(data);
				_.forEach(data, function(member) {
					member.treelvl = member.depth - minObj.depth
				});
				// ascending sort
				depthFirstTreeSort(data, nameCmp);
				$('#total').text(data.length);
				var vHtml = $('<div>');
				$.each(data, function( index, member ) {
					var row = $('#dup .lvl-container').clone();
					row.addClass(depthMap[member.treelvl]);
					row.find('.lvl-username').text(member.mb_id);
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
					if(member.mb_level < 9){/* member.mb_level > 1 조건 제거 0618_moon */ 
						row.find('.lvl img').prop('src','images/' + member.mb_level + 'eos.png');
					}
					row.find('.lvl').attr('mb_no',member.mb_no);
					vHtml.append(row);
				});
				
				$('#levelStructure').html(vHtml.html());
				$("html, body").animate({ scrollTop: 0 }, "fast");
				//console.log(data);
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
</head>
<body>
	<?include_once('mypage_head.php')?>
	<!-- <div id="overlay">
		<div id="text">
			<h2>Your browser is too small.</h2>
			<p>Level structure view works best on browsers that are at least 1235px wide.</p>
		</div>
	</div> -->

	<div class="main-container">
		<div id="body-wrapper" class="big-container-wrapper">
			<h2 class="gray level-structure-title" data-i18n="level.title" >Level Structure</h2> 
    <!--xx
      <span class="gray">( <span id="total"></span> Line )</span>
  -->
   <!--xx

      <span data-i18n="level.info" >Due to the amount of data, only 5 steps are shown</span>-->
			
			<div class="binary-tree-header-right">
				<input type="text" placeholder="Search member" name="binary_seach" id="binary_seach" data-i18n="[placeholder]level.searchMem" />
				<button type="button" class="search-button" id="search_btn" data-i18n="level.search">Search</button>
			</div>
			<div class="search_container">
				<div class="search_result" id="search_result">
					
				</div>
				<div class="result_btn">Close</div>
      </div>
      

<!--
			<div class="leg-view-container">
      
					<p>
						<span class="gray">
							<span class="mbId"><?=$member['mb_id']?></span>
						</span>
					</p>

			</div>
			-->
			<div id="levelStructure"></div>
		</div>
	</div>


 

	<div style="display:none;" id="dup">
		<div class="lvl-container" >
			<div class="lvl">
				<img src="" alt="lvl_img"> 
				<span class="lvl-username">IronMan</span> 
				<!-- <span class="gray">(<span class="childCnt">42</span>)</span> -->
				
       <!--xx <i class="fas "></i> -->
        <!--xx
				<span  class="description gray" >
					<span data-i18n="level.sponsored">Sponsored</span> : <span class="purple childCnt"></span> / 
					<span data-i18n="level.level">Lvl</span> : <span class="purple depth"></span> / 
					<span data-i18n="level.rank">Rank</span> : <i class='fas ranked'></i> / 
					<span data-i18n="level.sale30">30 Day</span> : <span class="purple sale30"></span> / 
					<span data-i18n="level.saleAll">All</span> : <span class="purple saleAll"></span>
					
        </span>
  -->
      </div>
      <!--xx

			<div class="lvl-info" style="">
				<div class="card-info-left">
					<span class="gray" data-i18n="level.name">Name</span> : <span class="blue name" ></span> <br>
					<span class="gray" data-i18n="level.enrollment">Enrollment Date</span> : <span class="blue enroll" ></span>  <br>
					<span class="gray" data-i18n="level.sponsor">Sponsor</span> : <span class="blue sponsor" ></span>  <br>
					<span class="gray" data-i18n="level.rank">Rank</span> : <span class="blue rank" ></span>  <br>
					<span class="gray" data-i18n="level.status">Status</span> : <span class="blue status" >Active</span>  <br>
					<span class="gray" data-i18n="level.email">Email</span> : <span class="blue email"></span> 
				</div>
				<div class="card-info-right">
					<span class="gray" data-i18n="level.pool">Pool</span> 1 : <span class="blue pool1"></span> <br>
					<span class="gray" data-i18n="level.pool">Pool</span> 2 : <span class="blue pool2"></span> <br>
					<span class="gray" data-i18n="level.pool">Pool</span> 3 : <span class="blue pool3"></span> <br>
					<span class="gray" data-i18n="level.pool">Pool</span> 4 : <span class="blue pool4"></span> <br>
					<span class="gray" data-i18n="level.pool">Pool</span> 5 : <span class="blue pool5"></span> <br>
					<span class="gray" data-i18n="level.gpu">GPU</span> : <span class="blue gpu"></span>
        </div>
  
				<div style="float:right;margin:20px;">
					<h5><span class="gray go">go</span></h5>
        </div>
        -->
			</div>			
		</div>
  </div>
  
</html>
