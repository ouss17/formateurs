<?php
if(isset($_FILES['excelFile']['name'])){
   $name = basename($_FILES['excelFile']['name']);
   if (file_exists($name)) {
      unlink($name);
   }
   // Nom du fichier
   $filename = $_FILES['excelFile']['name'];
   // Localisation
   $location = $filename;
   // file extension
   $file_extension = pathinfo($location, PATHINFO_EXTENSION);
   $file_extension = strtolower($file_extension);
   // Extension attendue (.xlsx sinon pas d'enrigtrement)
   $valid_ext = array("xlsx");
   $response = 0;
   if(in_array($file_extension,$valid_ext)){
      // Enregistrement fichier
      if(move_uploaded_file($_FILES['excelFile']['tmp_name'],'Tableau des formateurs.xlsx')){
         $response = 1;
      } 
   }
   echo $response;
   exit;
}
