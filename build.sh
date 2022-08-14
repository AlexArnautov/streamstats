docker-compose up -d
docker exec -it stream-stats-php81 composer install
chmod -R 777 runtime
chmod -R 777 web/assets
docker exec -it stream-stats-php81 npm install
docker exec -it stream-stats-php81 npm run dev
docker exec -it stream-stats-php81 php yii migrate --interactive=0
docker exec -it stream-stats-php81 php yii parse-streams
echo "**************************************************"
echo "*********     http://localhost/    ***************"
echo "**************************************************"