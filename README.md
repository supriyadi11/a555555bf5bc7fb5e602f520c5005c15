# REST API
REST API to send an email



## Installation

- Clone this repository
- Docker sudah terinstall https://www.docker.com/products/docker-desktop/
- Buka folder direktori di terminal dan jalankan perintah berikut:
```bash
docker-compose build

docker-compose up -d
```
- selanjutnya masuk ke folder direktori src dan jalankan perintah berikut
```bash
docker exec php_docker composer install

docker exec php_docker php app/database/migration.php
```
- Jalankan queue di terminal dengan perintah berikut
```bash
docker exec php_docker php worker
```
## pgAdmin
- Buka browser `http://localhost:5050` email `admin@email.com` password `admin123`
- register server dengan hostname/address `postgres` username `postgres` password `123`
![pgAdmin](pgadmin.jpg "pgAdmin")


## API Referensi

#### OAuth2
- Buka browser login dengan email
```
  http://localhost:8000/public/api/oauth
```
> **Note**
> Semua request endponit menggunakan header bearer authentication

#### Get all mails

```
  GET http://localhost:8000/public/api/mails
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `per_page` | `Int` | Jumlah data yang akan ditampilkan per halaman. *Default* 5 |
| `page` | `Int` | Nomor halaman yang akan dibuka. *Default* 1 |

#### Get mail

```
  GET http://localhost:8000/public/api/mail?id=2
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `Int` | **Required**. Id email yang akan diambil |

#### Create mail

```
  POST http://localhost:8000/public/api/mails
```

| Body | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `to`      | `String` | **Required**. Alamat email tujuan |
| `subject`      | `String` | **Required**. subjek email |
| `body`      | `String` | **Required**. Isi email |
| `status`      | `String` | **Required**. send atau draf |

> **Note**
> Setelah dibuat, email akan secara otomatis dimasukkan ke antrian dan akan mengirimkan email tersebut
