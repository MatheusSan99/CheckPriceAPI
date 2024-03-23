cp .env.example .env
touch "./logs/php-error.log"
chmod -f 777 "./logs/php-error.log"

docker compose build
docker compose up -d
docker exec abastecefacilapi composer install