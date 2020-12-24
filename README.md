### 1. weather_history/.env
Установить параметры подключения к БД

- DB_CONNECTION=mysql
- DB_HOST=127.0.0.1
- DB_PORT=3306
- DB_DATABASE=weather
- DB_USERNAME=root
- DB_PASSWORD=root
- DB_SOCKET=/Applications/MAMP/tmp/mysql/mysql.sock (Если через сокет) 

### 2. site/.env
Установить параметры подключения к БД

- DB_CONNECTION=mysql
- DB_HOST=127.0.0.1
- DB_PORT=3306
- DB_DATABASE=weather
- DB_USERNAME=root
- DB_PASSWORD=root
- DB_SOCKET=/Applications/MAMP/tmp/mysql/mysql.sock (Если через сокет) 

### 3. site/.env
Установить имя хоста проекта, откуда запрашивать погоду
- WEATHER_API_HOST = 'weather-history.loc'

### 4. Создать БД с именем как в DB_DATABASE

### 5. В консоле выполнить
```
cd weather_history
cd ./artisan migrate:refresh --seed
```
