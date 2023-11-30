<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

Class Setting_model extends CI_Model 
{
  public function getSettings()
  {
    return $this->db->where('code', 'application')->get('setting')->result();
  }

  public function saveSetting($data = array())
  {
    $this->db->insert('setting', $data);
    $setting_id = $this->db->insert_id();
    return $setting_id;
  }

  public function updateSetting($key, $updata_data)
  {
    $this->db->where('code', 'application');
    $this->db->where('key', $key);
    $this->db->update('setting', $updata_data);
  }

  public function parseSearchString(&$objects, $searchStr="")
  {
    $searchStr=trim(strtolower($searchStr));

    $pieces=preg_split('/[[:space:]]+/', $searchStr);
    $objects=array();
    $tmpstring='';
    $flag='';

    for($k=0; $k<count($pieces); $k++){
      while(substr($pieces[$k], 0, 1) == '('){
        $objects[]='(';
        if(strlen($pieces[$k]) > 1){
          $pieces[$k]=substr($pieces[$k], 1);
        }else{
          $pieces[$k]='';
        }
      }

      $postObjects=array();

      while(substr($pieces[$k], -1) == ')'){
        $postObjects[]=')';
        if(strlen($pieces[$k]) > 1){
          $pieces[$k]=substr($pieces[$k], 0, -1);
        }else{
          $pieces[$k]='';
        }
      }

      if((substr($pieces[$k], -1) != '"') && (substr($pieces[$k], 0, 1) != '"')){
        $objects[]=trim($pieces[$k]);

        for($j=0; $j<count($postObjects); $j++){
          $objects[]=$postObjects[$j];
        }
      }else{
        $tmpstring=trim(preg_replace('/"/', ' ', $pieces[$k]));

        if(substr($pieces[$k], -1 ) == '"'){
          $flag='off';
          $objects[]=trim(preg_replace('/"/', ' ', $pieces[$k]));

          for($j=0; $j<count($postObjects); $j++){
            $objects[]=$postObjects[$j];
          }
          unset($tmpstring);
          continue;
        }

        $flag='on';
        $k++;

        while(($flag == 'on') && ($k < count($pieces))){
          while(substr($pieces[$k], -1) == ')'){
            $postObjects[]=')';
            if(strlen($pieces[$k]) > 1){
              $pieces[$k]=substr($pieces[$k], 0, -1);
            }else{
              $pieces[$k]='';
            }
          }

          if(substr($pieces[$k], -1) != '"'){
            $tmpstring.=' ' . $pieces[$k];
            $k++;
            continue;
          }else{
            $tmpstring.=' ' . trim(preg_replace('/"/', ' ', $pieces[$k]));
            $objects[]=trim($tmpstring);

            for($j=0; $j<count($postObjects); $j++){
              $objects[]=$postObjects[$j];
            }

            unset($tmpstring);
            $flag='off';
          }
        }
      }
    }

    $temp=array();
    for($i=0; $i<(count($objects)-1); $i++){
      $temp[]=$objects[$i];
      if(($objects[$i] != 'and') &&
           ($objects[$i] != 'or') &&
           ($objects[$i] != '(') &&
           ($objects[$i+1] != 'and') &&
           ($objects[$i+1] != 'or') &&
           ($objects[$i+1] != ')') ){
        $temp[]='and';
      }
    }
    $temp[]=$objects[$i];
    $objects=$temp;

    $keywordCount=0;
    $operatorCount=0;
    $balance=0;
    for($i=0; $i<count($objects); $i++){
      if($objects[$i] == '(') $balance--;
      if($objects[$i] == ')') $balance++;
      if(($objects[$i] == 'and') || ($objects[$i] == 'or')){
        $operatorCount ++;
      }elseif(($objects[$i]) && ($objects[$i] != '(') && ($objects[$i] != ')')){
        $keywordCount++;
      }
    }

    if(($operatorCount < $keywordCount) && ($balance == 0)){
      return true;
    }else{
      return false;
    }
  }
}