#! /bin/sh

# gros hack pour changer l'adresse/role/password d'accès à postgis sans tout éditer à la main

old_host=localhost
old_dbname=osm
old_user=mapnik
old_password=ootaephi

new_host=osm2pgsql-monde.openstreetmap.fr
new_dbname=osm
new_user=layers
new_password=ootaephi

sed -i "s/name=\"host\">$old_host/name=\"host\">$new_host/" *.xml
sed -i "s/name=\"dbname\">$old_dbname/name=\"dbname\">$new_dbname/" *.xml
sed -i "s/name=\"user\">$old_user/name=\"user\">$new_user/" *.xml
sed -i "s/name=\"password\">$old_password/name=\"password\">$new_password/" *.xml

