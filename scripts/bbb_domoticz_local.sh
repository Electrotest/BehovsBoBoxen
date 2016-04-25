#! /bin/bash
### BEGIN INIT INFO
# Provides:          bbb_domoticz
# Required-Start:    $network $remote_fs $syslog
# Required-Stop:     $network $remote_fs $syslog
# Default-Start:     2 3 4 5
# Default-Stop:      0 1 6
# Short-Description: BehovsBoBox control script
# Description:       This daemon will start the BehovsBoBox Control system
### END INIT INFO

# Do NOT "set -e"

PATH=/sbin:/bin:/usr/sbin:/usr/bin:/usr/local/bin
DESC="BehovsBoBox Control System"
NAME=bbb_domoticz.py
USERNAME=pi

DAEMON=/home/pi/scripts/$NAME
DEAMON_OPTS=$2

PIDFILE=/var/run/bbb_domoticz.pid
#
SCRIPTNAME=/etc/init.d/bbb_domoticz.sh



#
# Modified to force deamon to execute in background
#

# Exit if the package is not installed
# [ -x "$DAEMON" ] || exit 0

# Load the VERBOSE setting and other rcS variables
. /lib/init/vars.sh
VERBOSE="yes"

# Define LSB log_* functions.
# Depend on lsb-base (>= 3.2-14) to ensure that this file is present
# and status_of_proc is working.
. /lib/lsb/init-functions

#
# Function that starts the daemon/service
#
do_start()
{
        # Return
        #   0 if daemon has been started
        #   1 if daemon was already running
        #   2 if daemon could not be started
        if [ -e $PIDFILE ]
        then 
          pid=$(<$PIDFILE)
          ps p $pid > /dev/null || rm -f $PIDFILE
        fi
#        start-stop-daemon --chuid $USERNAME --start  --pidfile $PIDFILE --exec $DAEMON --test > /dev/null \
#                || return 1
#        echo "OK to start"
         start-stop-daemon --start  --make-pidfile --pidfile $PIDFILE   --background --startas $DAEMON -- $DEAMON_OPTS || return 2
#         python /home/pi/bbb_addon/bbb_domoticz.sh &
}

#
# Function that stops the daemon/service
#
do_stop()
{
        # Return
        #   0 if daemon has been stopped
        #   1 if daemon was already stopped
        #   2 if daemon could not be stopped
        #   other if a failure occurred
        start-stop-daemon --stop --quiet --retry=TERM/30/KILL/5 --pidfile $PIDFILE
        RETVAL="$?"
        [ "$RETVAL" = 2 ] && return 2
        # Wait for children to finish too if this is a daemon that forks
        # and if the daemon is only ever run from this initscript.
        # If the above conditions are not satisfied then add some other code
        # that waits for the process to drop all resources that could be
        # needed by services started subsequently.  A last resort is to
        # sleep for some time.
        start-stop-daemon --stop --quiet --oknodo --retry=0/30/KILL/5 --exec $DAEMON
        [ "$?" = 2 ] && return 2
        # Many daemons don't delete their pidfiles when they exit.
        rm -f $PIDFILE
        return "$RETVAL"
}

case "$1" in
  start)
        [ "$VERBOSE" != no ] && log_daemon_msg "Starting $DESC" "$NAME"
        do_start
#        echo "status $?"
        case "$?" in
                0|1) [ "$VERBOSE" != no ] && log_end_msg 0 ;;
                2) [ "$VERBOSE" != no ] && log_end_msg 1 ;;
        esac
        ;;
  stop)
        [ "$VERBOSE" != no ] && log_daemon_msg "Stopping $DESC" "$NAME"
        do_stop
        case "$?" in
                0|1) [ "$VERBOSE" != no ] && log_end_msg 0 ;;
                2) [ "$VERBOSE" != no ] && log_end_msg 1 ;;
        esac
        ;;
  status)
        status_of_proc "$DAEMON" "$NAME" && exit 0 || exit $?
        ;;
  restart)
        log_daemon_msg "Restarting $DESC" "$NAME"
        do_stop
        case "$?" in
          0|1)
                do_start
                case "$?" in
                        0) log_end_msg 0 ;;
                        1) log_end_msg 1 ;; # Old process is still running
                        *) log_end_msg 1 ;; # Failed to start
                esac
                ;;
          *)
                # Failed to stop
                log_end_msg 1
                ;;
        esac
        ;;
  *)
        echo "Usage: $SCRIPTNAME {start|stop|status|restart}" >&2
        exit 3
        ;;
esac

:
