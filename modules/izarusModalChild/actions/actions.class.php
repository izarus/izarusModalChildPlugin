<?php

class izarusModalChildActions extends sfActions
{
  public function executeProcessForm(sfWebRequest $request){
    sfConfig::set('sf_web_debug', false);
    if(!$request->getParameter('imcd'))
      die;
    $secret = json_decode(base64_decode(strrev(str_replace('_','a',$request->getParameter('imcd')))),true);
    if(!isset($secret['c']) || !isset($secret['f']))
      die;
    $clase = $secret['c'];
    $table_name = $clase.'Table';
    $form_name = $secret['f'];
    $form = new $form_name();
    $obj_name = $form->getName();

    $componet_data = $this->getUser()->getAttribute('izarusModalChild'.$clase.$form_name);

    $clase = $secret['c'];
    $del_id = explode('-',$request->getParameter('id'));
    $id = $request->getParameter('id');
    $action = $request->getParameter('id');

    if(count($del_id)==2){
      //eliminacion "del-id"
      $id = $del_id[1];
    }else if($request->isMethod('post')){
      $id = $request->getParameter($obj_name);
      $id = $id['id'];
      $obj = $table_name::getInstance()->findOneById($id);
    }

    if($action=='+' || ($request->isMethod('post') && count($del_id)<2 && !$obj)){
      $form = new $form_name();
    }else{
      $obj = $table_name::getInstance()->findOneById($id);
      if($secret['opi'] == null && $secret['pi'] == null){
        if(!$obj)
          return $this->renderText('Forbidden');
      }else{
        if(!$obj || ($obj && $obj->get($secret['opi'])!= $secret['pi']))
          return $this->renderText('Forbidden');
      }
      $form = new $form_name($obj);
    }

    if($request->isMethod('post')){
      $obj_arr = $request->getParameter($obj_name);
      if(!($secret['opi'] == null && $secret['pi'] == null))
        $obj_arr[$secret['opi']] = $secret['pi'];
      $form->bind($obj_arr,$request->getFiles($obj_name));
      $form->updateObject();


      try{
        //Eliminar
        if($del_id[0]=='del' && !empty($id)){
          if($obj && (($secret['opi'] == null && $secret['pi'] == null) || $obj->get($secret['opi'])==$secret['pi'])){
            $obj->delete();
            $componet_data = $this->getUser()->getAttribute('izarusModalChild'.$clase.$form_name);

            $root = 'a';
            if(isset($componet_data['q']['root']))
              $root = $componet_data['q']['root'];

            $collection = $table_name::getInstance()->createQuery($root);
            if(!($secret['opi'] == null && $secret['pi'] == null))
              $collection->where($secret['opi'].' = ?',$secret['pi']);

            if(isset($componet_data['q']['where']))
              foreach($componet_data['q']['where'] AS $wh)
                $collection = $collection->andWhere($wh[0],$wh[1]);

            if(isset($componet_data['q']['left_joins']))
              foreach($componet_data['q']['left_joins'] AS $lj)
                $collection = $collection->leftJoin($lj);
            if(isset($componet_data['q']['order_by']))
              $collection = $collection->orderBy($componet_data['q']['order_by']);
            $collection = $collection->execute();

            return $this->renderText('OK'.'|@|'.$this->getPartial('izarusModalChild/table',array(
              'cols'=>$componet_data['c'],
              'collection'=>$collection,
              'messages'=>$componet_data['m'],
              'class'=>$clase,
              'form_class'=>$form_name,
              'buttons'=>$componet_data['b'],
              'enabled_actions'=>$componet_data['a'],
              'buttons_size'=>$componet_data['s'],
            )));
          }
          return $this->renderText('ERROR');

        //Insertar o Actualizar
        }else{
          if($form->isValid()){

            $form->getObject()->save();

            $componet_data = $this->getUser()->getAttribute('izarusModalChild'.$clase.$form_name);

            $root = 'a';
            if(isset($componet_data['q']['root']))
              $root = $componet_data['q']['root'];

            $collection = $table_name::getInstance()->createQuery($root);
            if(!($secret['opi'] == null && $secret['pi'] == null))
              $collection->where($secret['opi'].' = ?',$secret['pi']);

            if(isset($componet_data['q']['where']))
              foreach($componet_data['q']['where'] AS $wh)
                $collection = $collection->andWhere($wh[0],$wh[1]);

            if(isset($componet_data['q']['left_joins']))
              foreach($componet_data['q']['left_joins'] AS $lj)
                $collection = $collection->leftJoin($lj);
            if(isset($componet_data['q']['order_by']))
              $collection = $collection->orderBy($componet_data['q']['order_by']);
            $collection = $collection->execute();

            return $this->renderText('OK'.'|@|'.$this->getPartial('izarusModalChild/table',array(
              'cols'=>$componet_data['c'],
              'collection'=>$collection,
              'messages'=>$componet_data['m'],
              'class'=>$clase,
              'form_class'=>$form_name,
              'buttons'=>$componet_data['b'],
              'enabled_actions'=>$componet_data['a'],
              'buttons_size'=>$componet_data['s'],
            )));
          }
        }
      }catch(Exception $e){
        throw $e;
        return $this->renderText('ERROR');
      }

    }else{
      //Eliminación
      if(count($del_id)==2){
        $data = array();
        $data['form'] = new $form_name($obj);
        return $this->renderText('FORM'.'|@|'.$this->getPartial('izarusModalChild/deleteModal',$data));
      }
    }

    return $this->renderText('FORM'.'|@|'.$this->getPartial((!empty($componet_data['fp']))?$componet_data['fp']:'izarusModalChild/form',array('form'=>$form)));
  }

}