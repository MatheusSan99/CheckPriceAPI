#!/usr/bin/expect -f

# Baixar o go-pear.phar
spawn wget -O /tmp/go-pear.phar http://pear.php.net/go-pear.phar
expect eof

# Executar o go-pear.phar
spawn php /tmp/go-pear.phar

# Responder às perguntas de configuração
expect "1-11, 'all' or Enter to continue:"
send "\r"
expect eof
