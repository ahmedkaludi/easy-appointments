#!/bin/bash

#set plugin dir
#cd /easy-appointments
cd ..

rm -r ./trunk/*
mkdir ./trunk/img
mkdir -p ./trunk/css
mkdir -p ./trunk/css/scss
mkdir ./trunk/js
mkdir ./trunk/js/libs
mkdir ./trunk/js/libs/fullcalendar
mkdir ./trunk/src
mkdir ./trunk/vendor
mkdir ./trunk/fonts
mkdir ./trunk/languages

# meta script
# metascript ./main/js/admin.js > ./main/js/admin.prod.js
# metascript ./main/js/report.js > ./main/js/report.prod.js
# metascript ./main/js/settings.js > ./main/js/settings.prod.js

# shellcheck disable=SC2164
cd ./main

npm run scss
npm run meta-script

cd ../

cp -R ./main/js-react/build/wp-content/plugins/easy-appointments/ ./trunk/

cp ./main/main.php ./trunk/
cp ./main/readme.txt ./trunk/
cp -R ./main/css ./trunk/
cp -R ./main/css/scss ./trunk/css/
cp -R ./main/img ./trunk/
cp -R ./main/fonts ./trunk/
cp -R ./main/src ./trunk/
cp -R ./main/vendor ./trunk/
cp -R ./main/components ./trunk/
cp -R ./main/languages ./trunk/

cp -R ./main/js/libs/jquery-ui-timepicker-addon.js ./trunk/js/libs/
cp -R ./main/js/libs/jquery-ui-timepicker-addon-i18n.js ./trunk/js/libs/
cp -R ./main/js/libs/moment.min.js ./trunk/js/libs/
cp -R ./main/js/libs/chosen.jquery.min.js ./trunk/js/libs/
cp -R ./main/js/libs/mce.plugin.code.min.js ./trunk/js/libs/
cp -R ./main/js/libs/mce.plugin.code.min.js ./trunk/js/libs/
cp -R ./main/js/libs/jquery.validate.min.js ./trunk/js/libs/
cp -R ./main/js/libs/jquery-ui-i18n.min.js ./trunk/js/libs/
cp -R ./main/js/libs/fullcalendar/* ./trunk/js/libs/fullcalendar/

cp ./main/js/admin-router.js ./trunk/js/
cp ./main/js/admin.prod.js ./trunk/js/
cp ./main/js/frontend.js ./trunk/js/
cp ./main/js/frontend-bootstrap.js ./trunk/js/
cp ./main/js/report.prod.js ./trunk/js/
cp ./main/js/settings.prod.js ./trunk/js/
cp ./main/js/backbone.sync.fix.js ./trunk/js/
cp ./main/js/formater.js ./trunk/js/
cp ./main/js-react/build/static/js/bundle.js ./trunk/js/
#cp -R ./main/js-react/src/assets/  ./trunk/css/theme/
mkdir -p ./trunk/css/theme
cp ./main/js-react/build/static/css/main.css ./trunk/css/theme/

# set dist dir
cd ./trunk
zip -r ../latest/easy-appointment.zip *
