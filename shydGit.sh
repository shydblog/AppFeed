#!/bin/bash

cd existing_folder
git init
git remote add origin git@e.coding.net:shyd-unraid/unRAIDServer/AppFeed.git
git add .
git commit -m "Initial commit"
git push -u origin master -f