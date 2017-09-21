<?php
include_once('../models/db.class.php'); // call db.class.php
$db = new db(); // create a new object, class db()


if (!isset($_GET['id_ep']) OR $_GET['id_ep']=="")	{
  exit();
}


// Rendre votre modèle accessible
include '../models/eprouvette-model.php';

$oEprouvette = new EprouvetteModel($db,$_GET['id_ep']);

$essai=$oEprouvette->getTest();

$oEprouvette->dimension($essai['type'],$essai['dim1'],$essai['dim2'],$essai['dim3']);
$nb_dim=count($oEprouvette->dimDenomination());
$area = $oEprouvette->area();

$oEprouvette->niveaumaxmin($essai['c_1_type'], $essai['c_2_type'], $essai['c_type_1_val'], $essai['c_type_2_val']);

//on charge le model
include '../models/lstXMLforTS-model.php';
$oXMLforTS = new XMLforTSModel($db);
//on ajoute dans $variableTS_GPM les equivalent entre les noms de la base et les noms de variables TS
$variableTS_GPM = array();
foreach ($oXMLforTS->getAllXMLforTS($essai['id_test_type']) as $key => $value) {
  $variableTS_GPM[$value['ts']]=$essai[$value['xml']];
}

//on ajoute manuellement le nom d'ep
  $variableTS_GPM['SpecimenId'] = isset($essai['prefixe'])?$essai['prefixe'].'-'.$essai['nom_eprouvette']:$essai['nom_eprouvette'];



$xml_doc = new DOMDocument('1.0', 'utf-8');

$xml_doc->appendChild($AoAoVariableData_node = $xml_doc->createElement('ArrayOfArrayOfVariableData', ''));

$AoAoVariableData_node->setAttribute('xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
$AoAoVariableData_node->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');


$AoVariableData_node = $xml_doc->createElement('ArrayOfVariableData');
$AoAoVariableData_node->appendChild($AoVariableData_node);


foreach ($variableTS_GPM as $key => $value) {
  $VariableData_node = $xml_doc->createElement('VariableData');

  $VariableData_node->appendChild($name_node = $xml_doc->createElement('Name', $key));

  $VariableData_node->appendChild($Values = $xml_doc->createElement('Values'));
  $Values->appendChild($Value = $xml_doc->createElement('Value', $value));
  $AoVariableData_node->appendChild($VariableData_node);
}


$xml_string = $xml_doc->saveXML();

//echo $xml_string;



$fp = fopen('//SRV-DC01/data/labo/Computer/BDD/XMLforTS/'.$essai['n_fichier'].'.xml', 'w');
fwrite($fp, $xml_string);
fclose($fp);
?>
