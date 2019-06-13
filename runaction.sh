##### Constants #####
PROJECT_NAME="SITS Bookings - Backend"
LOCAL_DIR=~/Projects/phpStorm/fz-backend
REMOTE_DIR=${LOCAL_DIR}

TARGET_NAME_STAGE="METTELHORN"
REMOTE_DIR_STAGE="/home/simpleit/ch.simpleitsolutions.fz-backend/"
SSH_IP_STAGE="simpleitsolutions.ch"
SSH_PORT_STAGE="22"
SSH_USER_STAGE="simpleit"

TARGET_NAME_LIVE="METTELHORN"
REMOTE_DIR_LIVE="/home/simpleit/ch.simpleitsolutions.bookings/"
SSH_IP_LIVE="simpleitsolutions.ch"
SSH_PORT_LIVE="22"
SSH_USER_LIVE="simpleit"


DEFAULT_SYMFONY_APPLICATION=SITS
DEFAULT_SYMFONY_BUNDLE=App


##### Colors #####
BOLD=`tput bold`
RESET=`tput sgr0`           # Reset
RED=`tput setaf 1`
GREEN=`tput setaf 2`
YELLOW=`tput setaf 3`
BLUE=`tput setaf 4`
WHITE=`tput setaf 7`

cd ${LOCAL_DIR}

if [ "$1" == "deploy" ];then

	if [ "$2" == "stage" ]; then       
		target=Stage
		TARGET_NAME=${TARGET_NAME_STAGE}
    		REMOTE_DIR=${REMOTE_DIR_STAGE}
		SSH_USER=${SSH_USER_STAGE}
		SSH_IP=${SSH_IP_STAGE}
		SSH_PORT=${SSH_PORT_STAGE}
	fi

    	if [ "$2" == "live" ]; then
        	target=Live
        	TARGET_NAME=${TARGET_NAME_LIVE}
        	REMOTE_DIR=${REMOTE_DIR_LIVE}
        	SSH_USER=${SSH_USER_LIVE}
        	SSH_IP=${SSH_IP_LIVE}
        	SSH_PORT=${SSH_PORT_LIVE}
    	fi

    if [ "$3" == version-major ]; then
        php bin/console app:version:bump --major="+1" --minor="0" --patch="0" --prerelease="" --build="" 
    fi

    if [ "$3" == version-minor ]; then
        php bin/console app:version:bump --minor="+1" --patch="0" --prerelease="" --build=""
    fi

    if [ "$3" == version-patch ]; then
        php bin/console app:version:bump --patch="+1"
    fi

	debug=
	dryrun=
	if [ "$4" == "debug" ]; then
        	debug=Debug
        	dryrun=--dry-run
	fi

	echo
	echo "${BOLD}************* Deploying ${PROJECT_NAME} to: ${GREEN}${target}${RESET} ${BOLD}*************${RESET}"
	if [ "${debug}" == "Debug" ] ; then
    		echo " ${YELLOW}${BOLD} Debug Mode ${RESET}"
	fi
	echo "  LOCAL_DIR:  ${YELLOW}${LOCAL_DIR}${RESET}"
	echo "  REMOTE_DIR: ${YELLOW}${REMOTE_DIR}${RESET}"

        echo "  ...Syncing to ${target}..."

        echo "${RED}${BOLD}UPLOAD-SYNC ${RESET} -> ${target}/${TARGET_NAME} ! CONTINUE? (y/n)"
        images=
        media=
	response=
	read response
	if [ "$response" == "y" ]; then

        # -n Dry Run    -> set by adding a debug option
        # -v Verbose
        # -z Compress
        # -r Recursive


        if [ "$2" == "stage" ]; then
          rsync --exclude '.git' \
              --exclude=var \
              --exclude=.env* \
              -avzh . ${SSH_USER}@${SSH_IP}:${REMOTE_DIR}
        fi
        if [ "$2" == "live" ]; then
          rsync ${dryrun} -avzh \
              --exclude '.git' \
              --exclude=var \
              --exclude=.env* \
              --exclude=tests \
              --exclude=.circleci \
              . ${SSH_USER}@${SSH_IP}:${REMOTE_DIR}
        fi

        echo
        echo "  ${BLUE}...Removing /cache & /log files, setting Permissions and running Doctrine Migrations...${RESET}"
        if [ "${debug}" != "Debug" ] ; then
            ssh -p ${SSH_PORT} ${SSH_USER}@${SSH_IP} -t "
                cd ${REMOTE_DIR}
                rm -rf var/cache/*
                rm -rf var/logs/*
#                setfacl -R -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs
#                setfacl -R -m u:`whoami`:rwx app/cache app/logs
#                setfacl -dR -m u:www-data:rwx -m u:`whoami`:rwx app/cache app/logs
                /opt/cpanel/ea-php71/root/usr/bin/php bin/console cache:clear --env=prod --no-debug
                /opt/cpanel/ea-php71/root/usr/bin/php bin/console doctrine:migrations:migrate
            "
        fi
	fi

elif [ "$1" == "database-update" ]; then
	php app/console doctrine:schema:update --force

elif [ "$1" == "database-drop" ]; then
	php app/console doctrine:database:drop --force

elif [ "$1" == "database-create" ]; then
	php app/console doctrine:database:create

elif [ "$1" == "database-load" ]; then
	php app/console doctrine:fixtures:load -vvv

elif [ "$1" == "database-all" ]; then
	php app/console doctrine:database:drop --force
	php app/console doctrine:database:create
	php app/console doctrine:schema:update --force
        php app/console doctrine:fixtures:load

elif [ "$1" == "create-entity" ]; then
	app=${DEFAULT_SYMFONY_APPLICATION}
	bundle=$2 
	entity_class=$3

	php app/console doctrine:generate:entities ${app}/${bundle}Bundle/Entity/${entity_class}


elif [ "$1" == "assetic-dump" ]; then
        php app/console assetic:dump
        php app/console cache:clear


elif [ "$1" == "cache-clear" ]; then
        php app/console cache:clear

elif [ "$1" == "migrate" ]; then
        php app/console doctrine:migrations:migrate

elif [ "$1" == "migrate-diff" ]; then
        php app/console doctrine:migrations:diff

elif [ "$1" == "migrate-status" ]; then
        php app/console doctrine:migrations:status

elif [ "$1" == "version-bump" ]; then
		php bin/console app:version:bump -l

elif [ "$1" == "version-bump-major" ]; then
		php bin/console app:version:bump --major="+1" --minor="0" --patch="0"

elif [ "$1" == "version-bump-minor" ]; then
		php bin/console app:version:bump --minor="+1" --patch="0"

elif [ "$1" == "version-bump-patch" ]; then
		php bin/console app:version:bump --patch="+1"

elif [ "$1" == "server-run" ]; then
#	php bin/console server:run
	php bin/console -v server:run 127.0.0.1:8002

else
	echo
	echo
	echo
	echo '******************************************************'
	echo '** HELP - runaction.ch			  	    **'
	echo '******************************************************'
	echo
	echo START SERVER APPLICATION
	echo runaction server-run
	echo
	echo DEPLOY APPLICATION
	echo runaction deploy {stage\|live}
	echo
	echo DATABASE DROP
	echo runcation database-drop
	echo
        echo DATABASE CREATE
        echo runcation database-create
        echo
        echo DATABASE UPDATE \(forceful\)
        echo runcation database-update
        echo
        echo DATABASE LOAD
        echo runcation database-load
        echo
        echo DATABASE RECREATE
        echo runcation database-all
        echo
	echo CREATE SYMFONY BUNDLE
	echo runcation create-bundle {bundle-name}
	echo
	echo SYMFONY CREATE ENTITY
	echo runaction create-entity {bundle-name} {entity-class-name}
	echo
	echo SYMFONY RUN ASSETIC DEV
	echo runaction assetic-dump
	echo
	echo SYMFONY CLEAR CACHE DEV
	echo runaction cache-clear
        echo
        echo SYMFONY MIGRATION STATUS
        echo runaction migrate-status
        echo
        echo SYMFONY MIGRATION DIFFERENCE
        echo runaction migrate-diff
        echo
        echo SYMFONY DATABASE MIGRATE
        echo runaction migrate


fi

#php app/console assetic:dump
#php app/console doctrine:schema:update --force
#php app/console doctrine:generate:entities Aazp/BookingBundle/Entity/BookingRequest
#php app/console doctrine:fixtures:load
#php app/console doctrine:database:create
#php app/console doctrine:database:drop --force
