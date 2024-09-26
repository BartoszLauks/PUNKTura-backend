# Aplikacja scraper lub agregator danych z elementami automatycznej analizy

- Zbieranie danych z dwóch źródeł (strona z konferencjami naukowymi i strona rządowa).
- Porównywanie tych danych w celu znalezienia konferencji, które są wartościowe w kontekście przyznawania punktów.
- Może również wykorzystywać elementy automatyzacji i filtracji danych, aby pomóc użytkownikowi w wyszukiwaniu odpowiednich konferencji.

## Projekt na potrzeby uczelni.

## Opis

Aplikacja wyszukuje konferencje naukowe za które Ministerstwo Nauki i Szkolnictwa Wyższego przydziela punkty pracownikom uczelni.


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
