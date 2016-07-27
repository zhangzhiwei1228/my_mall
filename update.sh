#!/bin/bash
cd ~/wechat/dws
#git remote update -p
#git checkout -f origin/master
#git submodule update --init


git add . -A && git stash
git pull origin master
