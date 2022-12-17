## Kurulum

Kurulum yapıcağınız dosya dizinine gelerek aşağıdaki kodları sırası ile çalıştırınız.

- Repo Kurulumu
```
git clone https://github.com/kyakmehmet/laravel9RestApiAuth.git
```

- Dosya dizinine git
```
cd laravel9RestApiAuth
```

- Composer Kurulumu & Key
```
composer install
php artisan key:generate
```


- .env dosyasını oluştur
```
cp .env.example .env
```

- .env içerisinde yer alan veri tabanı bilgilerini kendinize göre değiştiriniz.
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yourdatabase
DB_USERNAME=yourusername
DB_PASSWORD=yourpassword
```

- JWT Token Oluşturma
```
php artisan jwt:secret
```

- Veri tabanında tabloları oluşturun
```
php artisan migrate
```

- Kayıt esnasında onay için mail gönderimi yapıldığından .env içerisindeki alana mail bilgilerinizi giriniz.

```
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## API İstekleri

#### BASE_URL = Kendi istek URL'nizi veriniz.

### 1 - Kullanıcı Kaydı

Kayıt işlemi için Ad Soyad, E-posta ve Şifre bilgilerinizi aşağıdaki istekte size özel alanları doldurarak POST isteği atmanız gerekmektedir.
```
curl
-F name=YOURNAME \
-F username=YOUREMAIL \
-F password=YOURPASSWORD \
-X POST BASE_URL/register
```
#### Sonuç
```json
{
    "status": "success",
    "message": "Üyeliğiniz başarılı bir şekilde yapıldı. Tarafınıza gönderilen epostayı onayladıktan sonra giriş yapabilirsiniz."
}
```
- **Not:** Tarafınıza gönderilen e-posta ile üyeliğinizi onaylaymanız gerekmektedir.

- Örnek; E-posta
![img.png](img.png)

### 2 - Kullanıcı Girişi

Giriş işlemi için E-posta ve Şifre bilgilerinizi aşağıdaki istekte size özel alanları doldurarak POST isteği atmanız gerekmektedir.
```
curl
-F username=YOUREMAIL \
-F password=YOURPASSWORD \
-X POST BASE_URL/login
```
#### Sonuç
```json
{
    "status": "success",
    "user": {
        "id": 1,
        "name": "Mehmet KIYAK",
        "email": "mkiyak511@gmail.com",
        "email_verified_at": null,
        "verification": 1,
        "created_at": "2022-12-17T09:14:22.000000Z",
        "updated_at": "2022-12-17T09:21:50.000000Z"
    },
    "authorisation": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbGFyYXZlbDlyZXN0YXBpYXV0aC50ZXN0L2FwaS9sb2dpbiIsImlhdCI6MTY3MTI2ODk2MywiZXhwIjoxNjcxMjcyNTYzLCJuYmYiOjE2NzEyNjg5NjMsImp0aSI6InhoekNJMHhPVUF2VFZ5VjAiLCJzdWIiOiIxIiwicHJ2IjoiMjNiZDVjODk0OWY2MDBhZGIzOWU3MDFjNDAwODcyZGI3YTU5NzZmNyJ9.Vw4w109Xjyqf03KdLlcF-iQElAzV-q019Eaqw6nZl8E",
        "type": "bearer"
    }
}
```

- **Not:** Gelen sonuçta yer alan authorisation token ile diğer işlemleri yürüteceksiniz.


### 3 - Giriş Yapan Kullanıcı Bilgilerini Alma

Giriş yaptıktan sonra sisteme giriş yapan kullanıcının bilgilerini için authorisation token bilgisi ile aşağıdaki istekte size özel alanları doldurarak GET isteği atmanız gerekmektedir.
```
curl
-H "Authorization: Bearer {token}"
-X GET BASE_URL/show
```
#### Sonuç
```json
{
    "status": "success",
    "user": {
        "id": 1,
        "name": "Mehmet KIYAK",
        "email": "mkiyak511@gmail.com",
        "email_verified_at": null,
        "verification": 1,
        "created_at": "2022-12-17T09:14:22.000000Z",
        "updated_at": "2022-12-17T09:21:50.000000Z"
    }
}
```

### 4 - Kullanıcı Bilgilerini Güncelle

Kullanıcı bilgilerini güncelleme için authorisation token bilgisi ile aşağıdaki istekte size özel alanları doldurarak PUT isteği atmanız gerekmektedir. {id} alanına güncellemek istediğiniz kullanıcını id sini giriniz.
```
curl
-H "Authorization: Bearer {token}"
-F name=YOURNAME \
-F username=YOUREMAIL \
-F password=YOURPASSWORD \
-X PUT BASE_URL/update/{id}
```
#### Sonuç
```json
{
    "status": "success",
    "message": "Kayıt başarıyla güncellendi!"
}
```

### 5 - Çıkış İşlemi

Çıkış işlemi için authorisation token bilgisi ile aşağıdaki istekte size özel alanları doldurarak POST isteği atmanız gerekmektedir.
```
curl
-H "Authorization: Bearer {token}"
-X GET BASE_URL/logout
```
#### Sonuç
```json
{
    "status": "success",
    "message": "Oturum başarıyla kapatıldı"
}
```
## Postman 

- Çalışmada test için postman üzerinde yapılan işlemlere aşağıda belirtilen koleksiyon içinden bakabilirsiniz. Koleksiyon repo içerisinde yer almaktadır.
- laravel9RestApiAuth.postman_collection.json
