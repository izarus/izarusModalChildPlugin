 <?php

class izarusModalChildComponents extends sfComponents
{
  public function executeView()
  {
    $table_name = $this->class.'Table';
    if(!isset($this->form_class))
      $this->form_class = $this->class.'Form';
    foreach($this->messages AS $m=>$str)
      $this->messages[$m] = str_replace(array('%option.singular%','%option.plural%'),array($this->singular,$this->plural),$str);
    $this->collection = $table_name::getInstance()->createQuery()->where($this->obj_parent_id.' = ?',$this->parent_id)->execute();
    
    $this->getUser()->setAttribute('izarusModalChild'.$this->class.$this->form_class,array('m'=>$this->messages,'c'=>$this->cols));
  }
}