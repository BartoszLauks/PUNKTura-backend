# Projekt na zaliczenie

### Projekt zespołowy

### Pierwsza instalacja

```bash
# 1
make build_dev

# 2
make start_dev

# 3 
make install

# wyłączenie apki
make stop
```

### Ponowne włączenie apki

```bash
# 1
make start_dev

# wyłączenie apki
make stop
```

### Ładownie danych do bazy

```bash
cat backup.sql | docker exec -i punktura-backend-mysql-dev /usr/bin/mysql -u root punktura-backend_dev
```

### Lokalne endpoint

```http request
http://localhost:8083/events/cfp
http://localhost:8083/events/mein
```