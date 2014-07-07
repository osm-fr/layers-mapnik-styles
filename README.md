Voici l'ensemble des styles au format mapnik 2.x xml faisant tourner http://layers.openstreetmap.fr

installation
------------
* Il vous faut une base postgresql avec un schéma osm2pgsql dont le style est celui par défaut (en date de 2013) 

* Il faut les options -G et -m à osm2pgsql au moins (et peut-être d'autres dont je ne me souviens plus)

* le serveur postgresql appelé avec role, password et nom de la base doit être placé dans un fichier :
config/db_settings.inc.xml
(prenez le config/db_settings-sample.inc.xml comme modèle et copier le dans config/db_settings.inc.xml )

* apache, mod_tile, renderd, mapnik 2.0
voir le wiki d'osm pour installer cette chaine pour un rendu carto avec mapnik
(dans /config vous trouverez des modèles de configuration pour renderd)



Vieux (mais peut revenir au gout du jour si bécanne peu puissante)
------------------------------------------------------------------

Etait (mais grace à la vitesse du SSD il semblerait jouable de se passer de cette obligation, si cela devait changer, voir l'historique
sur github https://github.com/osm-fr/layers-mapnik-styles) :

* Il faut à la table planet_osm_polygones un champs géométrique supplément nommé "simplified_way" qui contient une version
simpifiée de la géométrie contenue dans way (c'est pour accélérer)

Voici les commandes SQL a ajouter post import :
https://github.com/osm-fr/osm2pgsql-import-tools/blob/master/pre-post-import/after_create.sql

et voir les styles dans l'historique pour retrouver ceux faisant usage de cette colonne simplifiée
