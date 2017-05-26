<?php
// DataTables PHP library
include( "../DataTables/Editor-1.6.1/php/DataTables.php" );



// Alias Editor classes so they are easy to use
use
DataTables\Editor,
DataTables\Editor\Field,
DataTables\Editor\Format,
DataTables\Editor\Mjoin,
DataTables\Editor\Options,
DataTables\Editor\Upload,
DataTables\Editor\Validate;

// Build our Editor instance and process the data coming from _POST
Editor::inst( $db, 'lab_forcasts' )
->pkey( 'lab_forcasts.id_lab_forcast' )
->fields(
  Field::inst( 'lab_forcasts.texte_lab_forcast'),
  Field::inst( 'lab_forcasts.prio_lab_forcast'),
  Field::inst( 'lab_forcasts.id_icone_lab_forcast' )
      ->options( Options::inst()
          ->table( 'icones' )
          ->value( 'id_icone' )
          ->label( 'icone_name' )
      ),
  Field::inst( 'icones.icone_name' ),
  Field::inst( 'icones.icone_file' )

  )
  ->leftJoin( 'icones',     'icones.id_icone',          '=', 'lab_forcasts.id_icone_lab_forcast' )



  ->process($_POST)
  ->json();
  ?>
