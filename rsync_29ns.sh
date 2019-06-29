#!/bin/bash
#rsync -auvz \
rsync -rltOcuvz \
--exclude='.git/' --exclude='.settings/' --exclude='vendor/' --exclude='.htaccess'\
--exclude='.buildpath' --exclude='.editorconfig' --exclude='.gitattributes' \
--exclude='.gitignore' --exclude='.project' --exclude='public' --exclude='.env' --exclude='storage/' \
 /Users/hatamasa/29ns/ siennasnake2@29ns-prd:/home/siennasnake2/29ns/
