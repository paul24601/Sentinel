<?php
$file = 'submit.php';
$content = file_get_contents($file);

// Build the correct 34-character type string based on parameter types:
// s = string, i = integer, d = decimal/double

$types = [
    's', // reportType
    's', // date  
    's', // shift
    's', // shiftHours
    's', // productName
    's', // color
    's', // partNo
    's', // idNumber1
    's', // idNumber2
    's', // idNumber3
    's', // ejo
    's', // assemblyLine
    's', // machine
    's', // robotArm
    'i', // manpower (integer)
    's', // reg
    's', // ot
    'i', // totalRejectSum (integer)
    'i', // totalGoodSum (integer)
    'd', // injectionPressure (decimal)
    'd', // moldingTemp (decimal) 
    'd', // cycleTime (decimal)
    'd', // cavityCount (decimal)
    'd', // coolingTime (decimal)
    'd', // holdingPressure (decimal)
    's', // materialType
    'd', // shotSize (decimal)
    's', // finishingProcess
    's', // stationNumber
    's', // workOrder
    's', // finishingTools
    's', // qualityStandard
    's', // remarks
    's'  // userId
];

$correct_type_string = '"' . implode('', $types) . '"';

echo "Correct type string: " . $correct_type_string . " (length: " . (strlen($correct_type_string) - 2) . ")\n";

// Replace in file
$old_pattern = '"sssssssssssssissiidddddssssssss"';
$content = str_replace($old_pattern, $correct_type_string, $content);
file_put_contents($file, $content);

echo "Successfully updated submit.php with correct bind_param type string\n";
?>
