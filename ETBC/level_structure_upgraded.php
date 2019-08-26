<?php
include_once('./_common.php');
?>
<!doctype html>
<html lang="ko">
<head>
	<?include_once('common_head.php')?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.11/lodash.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/black/pace-theme-barber-shop.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="css/level_structure/style.css">
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
		function nameCmp(a, b) { return a.mb_no > b.mb_no; }
		
		var $selected;
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
				$.get( "level_structure_upgraded.mem.php", {
					mb_no: $(this).attr('mb_no')
				}).done(function( data ) {
					$selected.find('.name').text(data.mb_id);
					$selected.find('.sponsor').text(data.mb_recommend);
					$selected.find('.enroll').text(data.enrolled);
					$selected.find('.rank').text((data.mb_level -2) + ' Star');
					$selected.find('.email').text(data.mb_email);
					$selected.find('.pool1').text(data.it_pool1);
					$selected.find('.pool2').text(data.it_pool2);
					$selected.find('.pool3').text(data.it_pool3);
					$selected.find('.pool4').text(data.it_pool4);
					$selected.find('.pool5').text(data.it_pool5);
					$selected.find('.gpu').text(data.it_gpu);
				}).fail(function(e) {
					console.log( e );
				});
			});

			$(document).on('click','.lvl-username' ,function(e) {
				//console.log($(this).text());
				getLeg('<?=$member['mb_id']?>', $(this).text());
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
					alert("Please enter a keyword.");
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

			$("#search_result .result_btn").on('click',function(e){
				$('.search_container').removeClass("active");
			});
			
			$('#binary_seach').on('keydown',function(e){
				if(e.which == 13) {
					e.preventDefault();
					$('button.search-button').trigger('click');
				}
			});

			// 조직도 데이터 가져오기
			getList(<?=$member['mb_no']?>);

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
				//tt= data;
				var minObj = _.minBy(data, function(o) { return Number(o.depth); });
				// console.log(minObj);
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
					row.find('.childCnt').text(member.cnt);
					row.find('.go').attr('mb_no',member.mb_no);
					row.find('.go').attr('mb_id',member.mb_id);
					row.find('.lvl img').prop('src','images/' + (member.mb_level - 2) + 'star.png');
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
			})
		}

	</script>
</head>
<body>
	<?include_once('mypage_head.php')?>
	<div id="overlay">
		<div id="text">
			<h2>Your browser is too small.</h2>
			<p>Level structure view works best on browsers that are at least 1235px wide.</p>
		</div>
	</div>

	<div class="main-container">
		<div id="body-wrapper" class="big-container-wrapper">
			<h2 class="gray level-structure-title">Level Structure</h2> 
			<span class="gray">( <span id="total"></span> Line )</span> Due to the amount of data, only 10 steps are shown
			
			<div class="binary-tree-header-right">
				<input type="text" placeholder="Search member" name="binary_seach" id="binary_seach"/>
				<button type="button" class="search-button" id="search_btn">Search</button>
			</div>
			<div class="search_container">
				<div class="search_result" id="search_result">
					
				</div>
				<div class="result_btn">Close</div>
			</div>
			<div class="leg-view-container">
				<h5>Leg Stack</h5>
				<p>
					<span class="gray">
						<span class="mbId"><?=$member['mb_id']?></span>
					</span>
				</p>
			</div>
			<div id="levelStructure"></div>
		</div>
	</div>

	<div style="display:none;" id="dup">
		<div class="lvl-container" >
			<div class="lvl">
				<img src="images/6star.png" > 
				<span class="lvl-username">IronMan</span> 
				<span class="gray">( <span class="childCnt">42</span> )</span>			
			</div>
			<div class="lvl-info" style="">
				<div class="card-info-left">
					<span class="gray">Name:</span> <span class="blue name" ></span> <br>
					<span class="gray">Enrollment Date:</span> <span class="blue enroll" >Jan 01, 2018</span>  <br>
					<span class="gray">Sponsor:</span> <span class="blue sponsor" >Thanos</span>  <br>
					<span class="gray">Rank:</span> <span class="blue rank" >6 Star</span>  <br>
					<span class="gray">Status:</span> <span class="blue status" >Active</span>  <br>
					<span class="gray">Email:</span> <span class="blue email">example@email.com</span> 
				</div>
				<div class="card-info-right">
					<!-- <span class="gray">Mining Pools</span> <br> -->
					<span class="gray">Pool 1:</span> <span class="blue pool1">4</span> <br>
					<span class="gray">Pool 2:</span> <span class="blue pool2">3</span> <br>
					<span class="gray">Pool 3:</span> <span class="blue pool3">1</span> <br>
					<span class="gray">Pool 4:</span> <span class="blue pool4">2</span> <br>
					<span class="gray">Pool 5:</span> <span class="blue pool5">2</span> <br>
					<span class="gray">GPU:</span> <span class="blue gpu">1</span>
				</div>
				<div style="float:right;margin:20px;">
					<h5><span class="gray go">go</span></h5>
				</div>
			</div>			
		</div>
	</div>
</html>
