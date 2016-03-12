<?php
function imc_parse_text($str,$obj){
  preg_match_all("|%(.*)%|U", $str, $match, PREG_PATTERN_ORDER);
  foreach($match[1] AS $field){
    if(!isset($obj[$field])){
      echo '<p  style="color:red"><b>ParseError</b>: Field <b>'.$field.'</b> not found on object <b>'.get_class($obj)."</b>.</p>";
      $str = str_replace('%'.$field.'%','',$str);
    }else{
      $str = str_replace('%'.$field.'%',$obj[$field],$str);
    }
  }
  
  preg_match_all("|#(.*)#|U", $str, $match, PREG_PATTERN_ORDER);
  foreach($match[1] AS $function){
    if(!is_object($obj->$function()) || (is_object($obj->$function()) && method_exists($obj->$function(),'__toString') && !is_null($obj->$function()->__toString())))
      $str = str_replace('#'.$function.'#',$obj->$function(),$str);
    else
      $str = str_replace('#'.$function.'#','',$str);
  }
  
  return html_entity_decode($str);
}