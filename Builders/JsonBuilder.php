<?php

// Get the json file content.
function getJson($FileName)
{
 return json_decode(file_get_contents('./data/'.$FileName),true) ;

}

// Get json objects by its id
function getJsonById($FileName,$id,$columns = [])
{
    //get the json file
  $jsons = getJson($FileName);

  //loop through the json file and return the object that mathces the relevant id.
    // return message if there is no match.
      foreach($jsons as $json){
          if($json['id'] == $id){
              // If specific columns are specified, filter the object to include only those columns
              if (!empty($columns) && array_filter($columns, 'strlen')) {
                  return array_intersect_key($json, array_flip($columns));
              } else {
                  return $json;
              }
          }
      }
    return "There is no record with id :".$id;
}

//Adding new object to json file
function addToJson($FileName,$data)
{
    // get the json file
    $jsons = getJson($FileName);
    // find the max id and define increment id(+1 to max_id) to the new object using array_merge in order to define the
    // id the first eleemt
    $max_id = max(array_column($jsons,'id'));
    $data = array_merge(['id' => $max_id+1], $data);

    //add the new object to the json, save the file and return the new object
    $jsons[] = $data;
    file_put_contents('./data/'.$FileName, json_encode($jsons));
    return $jsons[$max_id];
}

// update json object
function upadteJson($FileName,$data,$id)
{
    // get the json file
    $jsons = getJson($FileName);
    //loop through the json file and find object that mathces the relevant id
    //merge the data to the json object, save the json file and return the udpated object
    // return message if there is no match
    foreach($jsons as  $i => $json){
        if($json['id'] == $id){
            $jsons[$i] = array_merge($json,$data);
            file_put_contents('./data/'.$FileName, json_encode($jsons));
            return  $jsons[$i];
        }

    }
    return "There is no record with id :".$id;
}

//Delete json object
function deleteFromJson($FileName,$id)
{
    // get the json file
  $jsons = getJson($FileName);
  //loop through the json file and find object that mathces the relevant id
    // delete the match object and keep the array indexes order by array_splice
    foreach($jsons as  $i => $json){
        if($json['id'] == $id){
            array_splice($jsons,$i,1);
        }
    }

    //save the json file
    file_put_contents('./data/'.$FileName, json_encode($jsons));
}
