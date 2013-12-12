<?php

// Format attendu du fichier : ref insee,nom commune,w ou l,km de voirie
// w sont les cours d'eau, l les voies terrestres
$file_to_import="./stats.csv";

/* Petite bidouille pour fournir le code source de moi même si ?src est passé en paramètre --sly */
if (isset($_GET['src']))
{
  header("Content-Type: text/plain; charset=UTF-8"); // de toute façon ça se lance dans un cron, sauf cas du :
  die(file_get_contents($_SERVER['SCRIPT_FILENAME'])); 
}
else
  header("Content-type: text/html; charset=UTF-8");


/* Connexion à la base PostresSQL */
if (!$c=pg_connect("dbname=osm2pgsql"))
  die("Erreur connexion SQL");
$row=1;
pg_query("delete from cadastre");
if (($handle = fopen($file_to_import, "r")) !== FALSE) 
{
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
    {
      if ($row>1)
      {
        $num = count($data);
	if ($num !=2 )
	  print("Erreur à la ligne $row, la ligne n'a pas 2 éléments\n");
	else
	{
	  $insertion="INSERT into cadastre VALUES ('$data[0]',round($data[1]))";
	  pg_query($insertion);
	}
      }
	$row++;
    }
    fclose($handle);
}
pg_close($c);
?>
