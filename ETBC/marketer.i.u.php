<?php
include_once('./_common.php');
include_once(G5_LIB_PATH.'/mailer.lib.php');

if($_POST['content'] && $member['mb_id']){
    if(!$_POST['idx']){
        $sql = " insert into marketer (content, writer,create_dt) values (";
        $sql .= "'".$_POST['content']."', ";
        $sql .= "'".$member['mb_id']."', ";
        $sql .= "now() ) ";
        sql_query($sql);

        $wr_id = sql_insert_id();
        // echo $wr_id;

        ///// 파일 업로드 시작 ////////
        $bo_table = 'marketer';
        // 디렉토리가 없다면 생성합니다. (퍼미션도 변경하구요.)
        @mkdir(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);
        @chmod(G5_DATA_PATH.'/file/'.$bo_table, G5_DIR_PERMISSION);
        $chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));
        // 가변 파일 업로드
        $file_upload_msg = '';
        $upload = array();
        for ($i=0; $i<count($_FILES['bf_file']['name']); $i++) {
            if($_FILES['bf_file']['name'][$i] == '') continue;
            $upload[$i]['file']     = '';
            $upload[$i]['source']   = '';
            $upload[$i]['filesize'] = 0;
            $upload[$i]['image']    = array();
            $upload[$i]['image'][0] = '';
            $upload[$i]['image'][1] = '';
            $upload[$i]['image'][2] = '';
            $upload[$i]['del_check'] = false;

            $tmp_file  = $_FILES['bf_file']['tmp_name'][$i];
            $filesize  = $_FILES['bf_file']['size'][$i];
            $filename  = $_FILES['bf_file']['name'][$i];
            $filename  = get_safe_filename($filename);

            // 서버에 설정된 값보다 큰파일을 업로드 한다면
            if ($filename) {
                if ($_FILES['bf_file']['error'][$i] == 1) {
                    $file_upload_msg .= '\"'.$filename.'\" 파일의 용량이 서버에 설정('.$upload_max_filesize.')된 값보다 크므로 업로드 할 수 없습니다.\\n';
                    continue;
                }
                else if ($_FILES['bf_file']['error'][$i] != 0) {
                    $file_upload_msg .= '\"'.$filename.'\" 파일이 정상적으로 업로드 되지 않았습니다.\\n';
                    continue;
                }
            }

            if (is_uploaded_file($tmp_file)) {
                // 관리자가 아니면서 설정한 업로드 사이즈보다 크다면 건너뜀
                if (!$is_admin && $filesize > 1048576) {
                    $file_upload_msg .= '\"'.$filename.'\" 파일의 용량('.number_format($filesize).' 바이트)이 게시판에 설정('.number_format(1048576).' 바이트)된 값보다 크므로 업로드 하지 않습니다.\\n';
                    continue;
                }

                //=================================================================\
                // 090714
                // 이미지나 플래시 파일에 악성코드를 심어 업로드 하는 경우를 방지
                // 에러메세지는 출력하지 않는다.
                //-----------------------------------------------------------------
                $timg = @getimagesize($tmp_file);
                // image type
                if ( preg_match("/\.({$config['cf_image_extension']})$/i", $filename) ||
                    preg_match("/\.({$config['cf_flash_extension']})$/i", $filename) ) {
                    if ($timg['2'] < 1 || $timg['2'] > 16)
                        continue;
                }
                //=================================================================

                $upload[$i]['image'] = $timg;

                // 4.00.11 - 글답변에서 파일 업로드시 원글의 파일이 삭제되는 오류를 수정
                if ($w == 'u') {
                    // 존재하는 파일이 있다면 삭제합니다.
                    $row = sql_fetch(" select bf_file from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '$wr_id' and bf_no = '$i' ");
                    @unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.$row['bf_file']);
                    // 이미지파일이면 썸네일삭제
                    if(preg_match("/\.({$config['cf_image_extension']})$/i", $row['bf_file'])) {
                        delete_board_thumbnail($bo_table, $row['bf_file']);
                    }
                }

                // 프로그램 원래 파일명
                $upload[$i]['source'] = $filename;
                $upload[$i]['filesize'] = $filesize;

                // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
                $filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

                shuffle($chars_array);
                $shuffle = implode('', $chars_array);

                // 첨부파일 첨부시 첨부파일명에 공백이 포함되어 있으면 일부 PC에서 보이지 않거나 다운로드 되지 않는 현상이 있습니다. (길상여의 님 090925)
                $upload[$i]['file'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);

                $dest_file = G5_DATA_PATH.'/file/'.$bo_table.'/'.$upload[$i]['file'];

                // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
                $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['bf_file']['error'][$i]);

                // 올라간 파일의 퍼미션을 변경합니다.
                chmod($dest_file, G5_FILE_PERMISSION);
            }
        }

        // 나중에 테이블에 저장하는 이유는 $wr_id 값을 저장해야 하기 때문입니다.
        for ($i=0; $i<count($upload); $i++)
        {
            if (!get_magic_quotes_gpc()) {
                $upload[$i]['source'] = addslashes($upload[$i]['source']);
            }

            $sql = " insert into {$g5['board_file_table']}
                        set bo_table = '{$bo_table}',
                            wr_id = '{$wr_id}',
                            bf_no = '{$i}',
                            bf_source = '{$upload[$i]['source']}',
                            bf_file = '{$upload[$i]['file']}',
                            bf_content = '{$bf_content[$i]}',
                            bf_download = 0,
                            bf_filesize = '{$upload[$i]['filesize']}',
                            bf_width = '{$upload[$i]['image']['0']}',
                            bf_height = '{$upload[$i]['image']['1']}',
                            bf_type = '{$upload[$i]['image']['2']}',
                            bf_datetime = '".G5_TIME_YMDHIS."' ";
            sql_query($sql);
        }


        // 메일 보내기 시작 
        $subject = "[".$config['cf_title']."] MP Request Result";
        $recipient  = sql_fetch("select * from g5_member where mb_id = '".$member['mb_id']."'");
        $status = 'R';

        ob_start();
        include_once ('./mp_status_mail.php');
        $content = ob_get_contents();
        ob_end_clean();

        // mailer(발신자 이름, 발신자 메일, 수신자 메일, 메일 제목, 내용, 1);
        mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $recipient['mb_email'], $subject, $content, 1);
        
    }else if($_POST['idx']){ 
         // 사용자가 수정하는 부분인데 지우진 않음...
        $sql = " update marketer set ";
        $sql .= " content = '".$_POST['content']."' ";
        $sql .= " where writer = TRIM(".$member['mb_id'].") and idx =".$_POST['idx'] ;
        sql_query($sql);
    }
}
goto_url('./marketer.php');

?>
