#!/bin/bash
DIR="$(cd -P "$( dirname "${BASH_SOURCE[0]}" )" && pwd)"
cd "$DIR"

# Автоматический рестарт сервера
DO_LOOP="yes"

while getopts "p:f:l" OPTION 2> /dev/null; do
	case ${OPTION} in
		p)
			PHP_BINARY="$OPTARG"
			;;
		f)
			POCKETMINE_FILE="$OPTARG"
			;;
		l)
			DO_LOOP="yes"
			;;
		\?)
			break
			;;
	esac
done

if [ "$PHP_BINARY" == "" ]; then
	if [ -f ./bin/php7/bin/php ]; then
		export PHPRC=""
		PHP_BINARY="./bin/php7/bin/php"
	elif [[ ! -z $(type php) ]]; then
		PHP_BINARY=$(type -p php)
	else
		echo "Не удалось найти работающий бинарник PHP"
		exit 1
	fi
fi

if [ "$POCKETMINE_FILE" == "" ]; then
	if [ -f ./PocketMine-MP.phar ]; then
		POCKETMINE_FILE="./PocketMine-MP.phar"
	elif [ -f ./LiteCore*.phar ]; then
		POCKETMINE_FILE="./LiteCore*.phar"
	elif [ -f ./src/pocketmine/PocketMine.php ]; then
		POCKETMINE_FILE="./src/pocketmine/PocketMine.php"
	else
		echo "Ядро установлено некорректно!"
		exit 1
	fi
fi

LOOPS=0

set +e
while [ "$LOOPS" -eq 0 ] || [ "$DO_LOOP" == "yes" ]; do
	if [ "$DO_LOOP" == "yes" ]; then
		"$PHP_BINARY" "$POCKETMINE_FILE" $@
	else
		exec "$PHP_BINARY" "$POCKETMINE_FILE" $@
	fi
	if [ "$DO_LOOP" == "yes" ]; then
		if [ ${LOOPS} -gt 0 ]; then
			echo "Restarted $LOOPS times"
		fi
		echo "Чтобы выйти из цикла, нажмите CTRL + C сейчас. В ином случае подождите 2 секунды, сервер перезапустится."
		sleep 2
		((LOOPS++))
	fi
done
