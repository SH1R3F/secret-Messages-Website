<?php


class Pagination{
  public $data;

  public function paginate($values, $limit){
    $total = count($values);

    if(Input::get('page')){
      $current = Input::get('page');
    }else{
      $current = 1;
    }

    $counts = ceil($total/$limit);
    $params = ($current-1) * $limit;
    $this->data = array_slice($values, $params, $limit);
    for($x=1; $x<=$counts; $x++){
      $numbers[] = $x;
    }
    return $numbers;
  }

  public function fetchResults(){
    return $this->data;
  }

}
