<?php
// ヘッダーHTML呼び出し
  require_once './tpl/header.html';
?>
<form action="./index.php" method="post"
enctype='multipart/form-data'>
<?php //返信フラグに応じて返信元データを送信する ?>
<input type="hidden" name="reply_id" value="<?php echo $re; ?>">
<?php echo $msg; ?>
<br>
ニックネーム<input type="text" name="name">
ジャンル
<select name="category">
<option value="映画">映画</option>
<option value="本">本</option>
<option value="音楽">音楽</option>
</select>
<br>
メッセージ<textarea name="msg" cols="50" rows="10"></textarea>
<br>
画像 <input type='file' name='up_file'>
<input type="submit" name="enter" value="投稿">
<hr>
ジャンル選択
<select name="category_search">
<option value="">検索ジャンル</option>
<option value="映画">映画</option>
<option value="本">本</option>
<option value="音楽">音楽</option>
</select>
<input type="submit" name="enter" value="検索">
</form>
<?php
// 配列に格納したDBデータ一覧をループして取り出す
  foreach($t_post as $val){
    ?><div class="main"><?php
    echo $val['id'];
    ?>番 ニックネーム: <?php
    echo $val['name'];
    ?> さん <?php
    echo $val['post_date'];
    ?> <a href="./index.php?re=<?php echo $val['id'];?>">返信</a> <a href="./index.php?del=<?php echo $val['id'];
    ?>">削除</a><br><br><img src="<?php echo UPLOAD_PATH.$val['id']; ?>.jpg" width="400"><?php
    ?><br><br><?php
    echo $val['msg'];
    ?><br><br>
    </div><?php
    foreach($val['replyData'] as $val2){
      ?><div class="re"><?php
      echo $val2['id'];
      ?>番 ニックネーム: <?php
      echo $val2['name'];
      ?> さん <?php
      echo $val2['post_date'];
      ?> <a href="./index.php?del=<?php echo $val2['id'];
      ?>">削除</a><br><br><img src="<?php echo UPLOAD_PATH.$val2['id']; ?>.jpg" width="400"><?php
      ?><br><br><?php
      echo $val2['msg'];
      ?><br><br>
      </div><?php
      }
  }
  // フッターHTML呼び出し
  require_once './tpl/footer.html';
?>