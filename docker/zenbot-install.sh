#!/usr/bin/env bash

cd /home
git clone -b unstable https://github.com/deviavir/zenbot.git
cd zenbot/
npm config set registry http://registry.npmjs.org
npm install