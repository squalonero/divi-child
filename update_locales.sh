wp i18n make-pot . languages/divi-child.pot --exclude="node_modules","lib" --debug
wp i18n update-po ./languages/divi-child.pot ./languages
wp i18n make-json ./languages --no-purge