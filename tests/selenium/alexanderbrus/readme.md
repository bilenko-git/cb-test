Manual on Ubuntu:

0. Run test (if Selenium Server and vendors are installed; if not see step 1 and 2):
   cd alexanderbrus/
   vendor/bin/phpunit <Test Name>.php 
   ex.: vendor/bin/phpunit UploadTest.php
   
1. Install composer and dependencies
   1. cd alexanderbrus/
   2. curl -sS https://getcomposer.org/installer | php
   3. php composer.phar install

2. Install Selenium Server
   1. mkdir /usr/lib/selenium cd /usr/lib/selenium
   2. Replace the following link with the latest version. Find the latest version here: http://selenium-release.storage.googleapis.com/index.html
      wget http://selenium-release.storage.googleapis.com/2.43/selenium-server-standalone-2.43.1.jar
   3. ln -s selenium-server-standalone-2.43.1.jar selenium-server-standalone.jar
   4. mkdir -p /var/log/selenium chmod a+w /var/log/selenium
   5. nano /etc/init.d/selenium
      add follow lines:
      #!/bin/bash

      case "${1:-''}" in
          'start')
              if test -f /tmp/selenium.pid
              then
                  echo "Selenium is already running."
              else
                          export DISPLAY=localhost:99.0 && java -jar /usr/lib/selenium/selenium-server-standalone.jar -port 4444 -trustAllSSLCertificates > /var/log/selenium/output.log 2> /var/log/selenium/error.log & echo $! > /tmp/selenium.pid
                  echo "Starting Selenium..."

                  error=$?
                  if test $error -gt 0
                  then
                      echo "${bon}Error $error! Couldn't start Selenium!${boff}"
                  fi
              fi
          ;;
          'stop')
              if test -f /tmp/selenium.pid
              then
                  echo "Stopping Selenium..."
                  PID=`cat /tmp/selenium.pid`
                  kill -3 $PID
                  if kill -9 $PID ;
                      then
                          sleep 2
                          test -f /tmp/selenium.pid && rm -f /tmp/selenium.pid
                      else
                          echo "Selenium could not be stopped..."
                      fi
              else
                  echo "Selenium is not running."
              fi
              ;;
          'restart')
              if test -f /tmp/selenium.pid
              then
                  kill -HUP `cat /tmp/selenium.pid`
                  test -f /tmp/selenium.pid && rm -f /tmp/selenium.pid
                  sleep 1
                  export DISPLAY=localhost:99.0 && java -jar /usr/lib/selenium/selenium-server-standalone.jar -port 4444 -trustAllSSLCertificates > /var/log/selenium/output.log 2> /var/log/selenium/error.log & echo $! > /tmp/selenium.pid
                  echo "Reload Selenium..."
              else
                  echo "Selenium isn't running..."
              fi
              ;;
          *)      # no parameter specified
              echo "Usage: $SELF start|stop|restart|reload|force-reload|status"
              exit 1
          ;;
      esac

   6. chmod 755 /etc/init.d/selenium
   7. update-rc.d selenium defaults