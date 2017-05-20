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
Editor::inst( $db, 'machine_forcasts' )
->pkey( 'machine_forcasts.id_machine_forcast' )
->fields(
  Field::inst( 'machine_forcasts.id_machine_forcast')
  ->options( Options::inst()
      ->table( 'machines' )
      ->value( 'id_machine' )
      ->label( 'machine' )
  ),
    Field::inst( 'machines.machine' ),
  Field::inst( 'machine_forcasts.texte_machine_forcast'),
  Field::inst( 'machine_forcasts.prio_machine_forcast'),
  Field::inst( 'machine_forcasts.id_icone_machine_forcast' )
      ->options( Options::inst()
          ->table( 'icones' )
          ->value( 'id_icone' )
          ->label( 'icone_name' )
      ),
  Field::inst( 'icones.icone_name' ),
  Field::inst( 'icones.icone_file' )  

  )
  ->leftJoin( 'machines',     'machines.id_machine',          '=', 'machine_forcasts.id_machine_forcast' )
  ->leftJoin( 'icones',     'icones.id_icone',          '=', 'machine_forcasts.id_icone_machine_forcast' )



  ->process($_POST)
  ->json();
  ?>
