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
    
    if(!isset($this->parent_id))
      $this->parent_id = null;
    
    if(!isset($this->obj_parent_id))
      $this->obj_parent_id = null;
    
    if(!isset($this->query))
      $this->query = array();
    
    $root = 'a';
    if(isset($this->query['root']))
      $root = $this->query['root'];
    if(!isset($this->query['where']))
      $this->query['where'] = array();
    
    $this->collection = $table_name::getInstance()->createQuery($root);
    
    if(!($this->parent_id == null  && $this->obj_parent_id == null))
      $this->collection->where($this->obj_parent_id.' = ?',$this->parent_id);
    
    if(isset($this->query['left_joins']))
      foreach($this->query['left_joins'] AS $lj)
        $this->collection = $this->collection->leftJoin($lj);
    if(isset($this->query['where']))
      foreach($this->query['where'] AS $wh)
        $this->collection = $this->collection->andWhere($wh[0],$wh[1]);
    if(isset($this->query['order_by']))
      $this->collection = $this->collection->orderBy($this->query['order_by']);
    $this->collection = $this->collection->execute();
    
    if(!isset($this->call_js_function))
      $this->call_js_function = '';
      
    if(!isset($this->buttons))
      $this->buttons = array();
      
    if(!isset($this->enabled_actions))
      $this->enabled_actions = array();     
    
    if(!isset($this->enabled_actions['add']))
      $this->enabled_actions['add'] = true;
      
    if(!isset($this->enabled_actions['edit']))
      $this->enabled_actions['edit'] = true;
      
    if(!isset($this->enabled_actions['delete']))
      $this->enabled_actions['delete'] = true;
      
    if(!isset($this->buttons_size))
      $this->buttons_size = 'xs';
    
    $this->getUser()->setAttribute('izarusModalChild'.$this->class.$this->form_class,array(
      'm'=>$this->messages,
      'c'=>$this->cols,
      'fp'=>isset($this->form_partial)?$this->form_partial:'',
      'q'=>isset($this->query)?$this->query:array(),
      'b'=>$this->buttons,
      'a'=>$this->enabled_actions,
      's'=>$this->buttons_size,
    ));
    
  }
}