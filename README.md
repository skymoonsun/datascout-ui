# DataScout UI

1. Docker çalıştırma:
```
docker-compose up -d --build
```

2. PHP Docker container bash girme ve composer update:
```
docker exec -it datascout-php bash 
composer update
```

3. Veritabanı oluşturma:

   - phpMyAdmin'e girin (ID: root, PW: password).
   - ***datascout_db.sql*** dosyasını import edin.


<hr>

Dashboard: ***http://localhost/dashboard/***

phpMyAdmin: ***http://localhost:8081/***