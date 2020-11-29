#!/bin/bash

# first build react
cd ./main/js-react || exit
npm run build

# then copy/files with css and js old build
cd ..
npm run build
