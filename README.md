Voici l'ensemble des styles au format mapnik xml faisant tourner http://layers.openstreetmap.fr

installation
------------
* Il vous faut une base postgresql avec un schéma osm2pgsql dont le style est celui par défaut (en date de 2013) avec comme 
ajouts :
node,way ref:INSEE text linear
node,way ref:sandre text linear
node,way fixme text linear
node,way FIXME text linear
node,way note text linear
node,way validate:no_name text linear
node,way validate:no_ref text linear
node,way validate:my_own text linear
TODO : faire fonctionner ces styles avec le style par défaut sans cette particularité, ça permettra de plus facilement ré-utiliser
cette base pour d'autre besoin avec un schéma plus "classique"

* Il faut les options -G et -m à osm2pgsql au moins.

* Il faut à la table planet_osm_polygones un champs géométrique supplément nommé "simplified_way" qui contient une version
simpifiée de la géométrie contenue dans way (c'est pour accélérer)
TODO : voir s'il est possible de s'affranchir sans trop impacté les perfs de cette contrainte
Voici les commandes SQL a ajouter post import :
https://github.com/osm-fr/osm2pgsql-import-tools/blob/master/pre-post-import/after_create.sql

* le serveur postgresql appelé avec role, password et nom de la base est en dur dans le code (voir la fin d'un fichier xml)
 

* apache, mod_tile, renderd, mapnik 2.0
voir le wiki d'osm pour installer cette chaine pour un rendu carto avec mapnik
(dans /config vous trouverez des modèles de configuration pour renderd)

spécificité voirie-cadastre.xml
-------------------------------
Pour marcher, ce style utilise des données calculées en provenance du cadastre, et seul :
http://wiki.openstreetmap.org/wiki/User:Frodrigo sait comment les ré-générer 
un dump de cette table est dispo dans ce dépot ici : données-externes/cadastre.sql.gz
