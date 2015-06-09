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
    
    $root = 'a';
    if(isset($this->query['root']))
      $root = $this->query['root'];
    
    $this->collection = $table_name::getInstance()->createQuery($root)->where($this->obj_parent_id.' = ?',$this->parent_id);
    if(isset($this->query['left_joins']))
      foreach($this->query['left_joins'] AS $lj)
        $this->collection = $this->collection->leftJoin($lj);
    if(isset($this->query['order_by']))
      $this->collection = $this->collection->orderBy($this->query['order_by']);
    $this->collection = $this->collection->execute();
    
    if(!isset($this->call_js_function))
      $this->call_js_function = '';
    
    $this->getUser()->setAttribute('izarusModalChild'.$this->class.$this->form_class,array(
      'm'=>$this->messages,
      'c'=>$this->cols,
      'fp'=>isset($this->form_partial)?$this->form_partial:'',
      'q'=>isset($this->query)?$this->query:array(),
      'b'=>isset($this->buttons)?$this->buttons:array(),
    ));
  }
}