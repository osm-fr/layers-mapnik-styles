<?php
/*
Ce script est distribué sous licence BSD

L'auteur décline toute responsabilité quant au temps perdu et aux cheveux arrachés à tenter de comprendre ce code.

 script permettant le pré-calcul de statistiques de couverture voirie par commune française ;
 - Obtenir la liste codes INSEE présents dans OSM
 - Calculer la somme des km de voiries 
 - stocker le résultat dans un champ fait pour ça (appelé km_voirie)
 
 Il est supposé que la la table OSM s'appelle planet_osm_polygon

-- sly 02/2011

*/


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


// On comment par récupérer la liste de toutes les ref INSEE présentes dans le fichier fourni par frédéric

//Juste pour tester avec quelques communes
//$query_ref_insee="select refinsee,km from cadastre where refinsee like '4400%' order by refinsee";
$query_ref_insee="select refinsee,km from cadastre order by refinsee";


$res=pg_query($query_ref_insee);

$i=0;
$contenu_csv="";
while($communes_a_pre_calculer=pg_fetch_object($res))
{
  // Pour chaque commune on calcul, la longueur totale de voirie
  // ça prend environ 0.2 seconde par commune en moyenne (mais ça varie bien sûr énormément entre une commune sans rues et paris !)
$query_km_voirie_commune="select round(sum(st_length(st_transform(st_intersection(f.way,l.way),2154)))/1000) as longueur_km
  from planet_osm_line as l,planet_osm_polygon as f 
  where f.\"ref:INSEE\"='$communes_a_pre_calculer->refinsee' 
  and 
  l.highway in ('unclassified','secondary','primary','tertiary','service','trunk','motorway','residential','track')
  and 
  f.way && l.way group by f.\"ref:INSEE\"";
  $res2=pg_query($query_km_voirie_commune);
  
  $km_voirie=pg_fetch_object($res2);
  
  // Des fois que ça foire (cas de commune mal formée que postGIS ne sait pas gérer), on impose un 0
  if (!is_numeric($km_voirie->longueur_km))
    $km_voirie->longueur_km=0;

  // On stoque en précalcul cette information
  $mise_a_jour_km_voirie="update planet_osm_polygon 
  set km_voirie= $km_voirie->longueur_km
  where \"ref:INSEE\"='$communes_a_pre_calculer->refinsee'";
  
  $ligne_csv="$communes_a_pre_calculer->refinsee;$communes_a_pre_calculer->km;$km_voirie->longueur_km\n";
  $contenu_csv.="$ligne_csv";
  pg_query($mise_a_jour_km_voirie);
  $i++;
//  print($communes_a_pre_calculer->refinsee.".");
// Pour afficher un état d'avancement hyper simple (un . tout les 100 communes traitées)
//  if (($i/100)==round($i/100))
//    print(".\n");
}
$date=exec('grep timestamp ../../import-base-osm/state.txt | sed s/timestamp=// | sed s/\\\\\\\\//g | sed s/[TZ]/" "/g');
$en_tete="Référence INSEE;Km de voirie au cadastre;Km de voirie dans osm";
file_put_contents("voirie.csv","$en_tete\n$contenu_csv");
file_put_contents("suivi-voirie.txt","En date du $date\n$en_tete\n$contenu_csv");


?>
