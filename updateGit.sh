#!/bin/bash
cd /tmp/GitHub/AppFeed
git config --global user.email "1104196655@qq.com"
git config --global user.name "shyd"
cd existing_folder
git init
git remote add origin git@e.coding.net:shyd-unraid/unRAIDServer/AppFeed.git
git add .
git commit -m "Initial commit"
git push -u origin master -f


cd /tmp/GitHub/Squidly271.github.io
git config --global user.email "1104196655@qq.com"
git config --global user.name "shyd"
cd existing_folder
git init
git remote add origin git@e.coding.net:shyd-unraid/unRAIDServer/Squidly271.github.io.git
git add .
git commit -m "Initial commit"
git push -u origin master -f

