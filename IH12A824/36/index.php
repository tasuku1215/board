<?php
  // コンフィグ呼び出し
  require_once './../../config.php';
  // SQL接続
  $link=mysqli_connect(HOST,DB_USER,DB_PASS,DB_NAME);
  // 文字コードセット
  mysqli_set_charset($link,'utf8');
  // 削除ボタンを押されるとデリートフラグを立てる
  if(!empty($_GET['del'])){
    $sql="UPDATE t_post SET del_flg = '1' WHERE id = ".$_GET['del'];
    mysqli_query($link,$sql);
  }
  // 検索フラグで使用するSQL文の条件を変える
  if(empty($_POST['category_search'])){
    $sql="select * from t_post";
  }elseif($_POST['category_search']=='映画'){
    $sql="select * from t_post where category = '映画'";
  }elseif($_POST['category_search']=='本'){
    $sql="select * from t_post where category = '本'";
  }elseif($_POST['category_search']=='音楽'){
    $sql="select * from t_post where category = '音楽'";
  }
  $result=mysqli_query($link,$sql);
  // 初期化
  $msg='';
  $re=0;
  // 返信IDのフラグを立てる
  if(!empty($_GET['re'])){
    $msg=$_GET['re'].'番さんに返信する内容を入力してください';
    $re=$_GET['re'];
  }
  $max_id=0;
  if($result){
    // DBから取得したデータを配列に格納する
    // 削除フラグが存在する場合格納しない
    // 返信フラグが存在する場合返信先配列に返信元配列を格納する
  for($i=1;$row=mysqli_fetch_assoc($result);$i++){
      // $t_post[$row['id']]=$row;
      if($row['reply_id']==0&&$row['del_flg']==0){
        $t_post[$row['id']]['id']=$row['id'];
        $t_post[$row['id']]['name']=$row['name'];
        $t_post[$row['id']]['msg']=str_replace("\n","<br>",$row['msg']);
        $t_post[$row['id']]['category']=$row['category'];
        $t_post[$row['id']]['del_flg']=$row['del_flg'];
        $t_post[$row['id']]['replyData']=array();
        $t_post[$row['id']]['post_date']=$row['post_date'];
      }
      elseif($row['reply_id']!==0&&$row['del_flg']==0&&!empty($t_post[$row['reply_id']])){
        $t_post[$row['reply_id']]['replyData'][$row['id']]  ['id']=$row['id'];
        $t_post[$row['reply_id']]['replyData'][$row['id']]  ['name']=$row['name'];
        $t_post[$row['reply_id']]['replyData'][$row['id']]  ['msg']=str_replace("\n","<br>",$row['msg']);
        $t_post[$row['reply_id']]['replyData'][$row['id']]  ['category']=$row['category'];
        $t_post[$row['reply_id']]['replyData'][$row['id']]  ['reply_id']=$row['reply_id'];
        $t_post[$row['reply_id']]['replyData'][$row['id']]  ['post_date']=$row['post_date'];
      }
      // マックスIDを取得する
      if($max_id<$row['id']){
        $max_id=$row['id'];
      }
    }
  }
  // 新規書き込みデータが存在した場合DBにインサートする
  if(!empty($_POST['name'])&&!empty($_POST['category'])&&!empty($_POST['msg'])){
    $sql="insert into t_post(id,name,msg,category,reply_id,del_flg,post_date) values(".++$max_id.",'".$_POST['name']."','".$_POST['msg']."','".$_POST['category']."',".$_POST['reply_id'].",0,".date('YmdHis').")";
    // 新規にDBに追加したデータ分をDBデータ配列に格納する
    if($_POST['reply_id']==0){
      $t_post[$max_id]['id']=$max_id;
      $t_post[$max_id]['name']=$_POST['name'];
      $t_post[$max_id]['msg']=str_replace("\n","<br>",$_POST['msg']);
      $t_post[$max_id]['category']=$_POST['category'];
      $t_post[$max_id]['reply_id']=$_POST['reply_id'];
      $t_post[$max_id]['replyData']=array();
      $t_post[$max_id]['post_date']=date('Y-m-d H:i:s');
    }
    else{
      $t_post[$_POST['reply_id']]['replyData'][$max_id]  ['id']=$max_id;
      $t_post[$_POST['reply_id']]['replyData'][$max_id]  ['name']=$_POST['name'];
      $t_post[$_POST['reply_id']]['replyData'][$max_id]  ['msg']=str_replace("\n","<br>",$_POST['msg']);
      $t_post[$_POST['reply_id']]['replyData'][$max_id]  ['category']=$_POST['category'];
      $t_post[$_POST['reply_id']]['replyData'][$max_id]  ['reply_id']=$_POST['reply_id'];
      $t_post[$_POST['reply_id']]['replyData'][$max_id]  ['post_date']=date('Y-m-d H:i:s');
    }
    // JPG画像がアップロードされた場合ファイル名をID名にして格納
    if(!empty($_FILES)&&$_FILES['up_file']['type']=='image/jpeg'){
      move_uploaded_file($_FILES['up_file']['tmp_name'],UPLOAD_PATH.$max_id.'.jpg');
    }
    mysqli_query($link,$sql);
  }
  mysqli_close($link);
  // 初期化
  if(empty($t_post)){
    $t_post=array();
  }
  // var_dump($t_post);
  // 配列を反転させ投稿日時順の降順にする
  $t_post = array_reverse($t_post,true);
  // テンプレート呼び出し
  require_once './tpl/index.php';
?>