#!/bin/bash
#rsync -auvz \
rsync -rltOcuvz \
--exclude='.git/' --exclude='.settings/' --exclude='vendor/' --exclude='.htaccess'\
--exclude='.buildpath' --exclude='.editorconfig' --exclude='.gitattributes' --exclude='index.php' \
--exclude='.gitignore' --exclude='.project' --exclude='public' --exclude='images/users/' --exclude='images/posts/' \
 /Users/hatamasa/29ns/public/ siennasnake2@29ns-prd:/home/siennasnake2/www/29ns/
