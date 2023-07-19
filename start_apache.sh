#!/bin/bash

FLAVOR=$(uname -s)
LSB_RELEASE=$(command -v lsb_release 2> /dev/null)

echo "FLAVOR: $FLAVOR"
echo "LSB_RELEASE: $LSB_RELEASE"

if [ "$FLAVOR" = "Linux" ]; then
    if [ -n "$LSB_RELEASE" ]; then
        DISTRIBUTION=$(lsb_release -si | sed 's/Linux//')
    else
        DISTRIBUTION="Other"
    fi

    echo "DISTRIBUTION: $DISTRIBUTION"

    if [ "$DISTRIBUTION" = "Manjaro" ] || [ "$DISTRIBUTION" = "ManjaroLinux" ]; then
        sudo systemctl start httpd
    elif [ "$DISTRIBUTION" = "Ubuntu" ] || [ "$DISTRIBUTION" = "Debian" ]; then
        sudo service apache2 start
    else
        echo "Unsupported distribution: $DISTRIBUTION"
    fi
else
    echo "Unsupported operating system: $FLAVOR"
fi
