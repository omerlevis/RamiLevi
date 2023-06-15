<?php
require 'Builders\JsonBuilder.php';

//get and display the json file names , ask the user ro choose the json, deal with error in the choose
// store to varibale the chosen file name in order to send it to the functions
$fileNames = scandir('data', SCANDIR_SORT_NONE);
$fileNames = array_diff($fileNames, array('..', '.'));
$files_id=1;
$jsonFiles = [];
$validChoice = false;

while (!$validChoice) {
    echo "JSON files:". PHP_EOL;
    foreach ($fileNames as $fileName) {
        echo "$files_id: $fileName" . PHP_EOL;
        $jsonFiles[$files_id] = $fileName;
        $files_id++;
    }
    $selectedOption = intval(readline("Choose a json file: "));
    if ($selectedOption >= 1 && $selectedOption <= count($jsonFiles)) {
        $selectedFileName = $jsonFiles[$selectedOption];
        echo "Selected file: $selectedFileName" . PHP_EOL;
        $validChoice = true;
    } else {
        echo "Error: Invalid choice." . PHP_EOL;
    }
}

//print all the content from the json file
        echo "The Json content : ".PHP_EOL;
        echo json_encode(getJson($selectedFileName),JSON_PRETTY_PRINT).PHP_EOL;

        //get id of object, relevant columns to filter and print the object with the filtered columns(if set)
        $id = readline("Enter ID for spesific object from the json : ");
        $columns = readline("Enter colunms to display(comma-separated), for all prees Enter: ");
        $columns = explode(',', $columns);
        $columns = array_map('trim', $columns);
        echo "The chosen ID and columns : ";
        echo json_encode(getJsonById($selectedFileName,$id,$columns),JSON_PRETTY_PRINT).PHP_EOL;

        //get id for update object, skip for id and created_at columns, display the current value of the columns,
       // stroe the user input and send the data to function, print the updated object
        $id = readline("Enter ID for update object in the json : ");
        $data = getJsonById($selectedFileName,$id,[]);
        $indexNames = array_keys($data);
        $data_to_update = [];
        foreach ($indexNames as $indexName) {
            if($indexName === 'id' || $indexName ==='created_at' ){
                continue;
            }
            $data_to_update[$indexName] =  readline("Update $indexName (current value-$data[$indexName]):");
            $data_to_update[$indexName] = $data_to_update[$indexName]!=='' ?
                $data_to_update[$indexName] : $data[$indexName];
        }
        upadteJson($selectedFileName,$data_to_update,$id);
        echo "The updated data for id $id :".PHP_EOL;;
        echo json_encode(getJsonById($selectedFileName,$id,[]),JSON_PRETTY_PRINT).PHP_EOL;


   //add onject to json file, the id is autu-increment in the function so we skip the id input,
   //we use the data_to_update array from the update step in order to manipulate the relevant columns
   //stroe the user input and send the data to function, print the updated object
        echo "Add object to Json : ".PHP_EOL;
        foreach ($indexNames as $indexName) {
            if($indexName === 'id'){
                continue;
            }
            $data_to_update[$indexName] =  readline("Add $indexName :");
            $data_to_update[$indexName] = $data_to_update[$indexName]!=='' ?
                $data_to_update[$indexName] : null;
        }
        echo "The new object :".PHP_EOL;
        echo json_encode(addToJson($selectedFileName,$data_to_update),JSON_PRETTY_PRINT).PHP_EOL;

        //delete object in the json - ger id as input and send it to the function
        $id = readline("Enter ID for delete object in the json : ");
        deleteFromJson($selectedFileName,$id);
        echo "$id id has been deleted".PHP_EOL;
        echo json_encode(getJson($selectedFileName),JSON_PRETTY_PRINT).PHP_EOL;

