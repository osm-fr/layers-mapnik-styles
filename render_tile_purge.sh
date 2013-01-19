#!/bin/sh
#Ce script doit être lancé par l'utilisateur qui dispose des droits sur les dossiers des meta-tuiles
#root... on évitera étant donné qu'il y a un rm -rf !
#A lancer toutes les 12h il vire toutes les tuiles

# Pour ces styles, ne fait rien, c'est soit pas des styles, soit la procedure d'expiration plus longue est préférable
styles_a_ignorer="(renderd|2u|openriverboatmap|mapnik)"

set -e
tile_dir=$(grep tile_dir /etc/renderd.conf | cut -f2 -d\= | cut -f1 -d\ )


for sheet in $(egrep "^\[.*\]" /etc/renderd.conf | sed s/"\]"//g | sed s/"\["//g | egrep -v "$styles_a_ignorer") ; do
  rm $tile_dir$sheet -rf	
done

