<?php
/**
* h
* 引数$strに入っている文字列からHTMLタグを無効化する
* @param $str文字列HTMLタグが入っている可能性がある
* @return HTMLを無効化した文字列を返す
*/
function h($str){
  return htmlspecialchars($str,ENT_QUOTES);
}

/**
* session_delete
* すべてのsessionを削除する
*/
function session_delete(){
  $_SESSION = array();
  if(isset($_COOKIE[SESSION_name()])){
    setcookie(session_name(),'',time()-42000);
  }
  session_destroy();
}
?>