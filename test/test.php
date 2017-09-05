<?php
$xml_doc = new DOMDocument('1.0', 'utf-8');

$xml_doc->appendChild($AoAoVariableData_node = $xml_doc->createElement('ArrayOfArrayOfVariableData', ''));

$AoAoVariableData_node->setAttribute('xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
$AoAoVariableData_node->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');


$AoVariableData_node = $xml_doc->createElement('ArrayOfVariableData');
$AoAoVariableData_node->appendChild($AoVariableData_node);

$VariableData_node = $xml_doc->createElement('VariableData');

$VariableData_node->appendChild($name_node = $xml_doc->createElement('Name', 'TestName'));

$VariableData_node->appendChild($Values = $xml_doc->createElement('Values'));
$Values->appendChild($Value = $xml_doc->createElement('Value', 'TESTPOURPGO'));
$AoVariableData_node->appendChild($VariableData_node);


$xml_string = $xml_doc->saveXML();

echo $xml_string;



$fp = fopen('//SRV-DC01/data/labo/Computer/BDD/DataGPM-Test/file.xml', 'w');
fwrite($fp, $xml_string);
fclose($fp);
?>
