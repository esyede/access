# access

Paket role/permission sederhana untuk rakit framework.


### Fitur:

  - Otetikasi berbasis role/permission
  - Exception untuk handling error
  - Mudah digunakan / dikembangkan


### Instalasi

Jalankan perintah ini via rakit console:

```bash
php rakit package:install access
```

### Mendaftarkan paket

Tambahkan kode berikut ke file `application/packages.php`:

```php
'access' => ['autoboot' => true],
```

Lalu ubah Auth driver menjadi `'access'` di file `application/config/auth.php`:
```php
'driver' => 'access',
```

Lalu tinggal jalankan migrasi databasenya:

```bash
php rakit migrate access
```

Sekarang anda seharusnya sudah berhasil mengimpor semua tabel bawaan access,
lengkap dengan sampel user bernama **admin**, dengan kata sandi **password**.


### Penggunaan

Paket ini sengaja dibuat sangat sederhana. Anda tinggal menambahkan
Users, Roles dan Permissions seperti menambahkan record ke Model pada umumnya.

```php
use Esyede\Access\Models\User;
use Esyede\Access\Models\Role;
use Esyede\Access\Models\Permission;


// kode lain disini..


$user = new User();
$role = new Role();
$permission = new Permission();
```

Seluruh model berada di namespace `Esyede\Access\Models` sehingga
tidak akan bercampur dengan model anda.



Relasi tabel-tabelnya adalah sebagai berikut:

  - Roles _has-many_ dan _belongs-to_ ke Users
  - Users _has-many_ dan _belongs-to_ ke Roles
  - Roles _has-many_ dan _belongs-to_ ke Permissions
  - Permissions _has-many_ dan _belongs-to_ ke Roles

Relasi - relasi ini juga ditangani via ORM:

```php
$role->permissions()->sync([$permission->id]);
```


Informasi lebih lanjut mengenai relasi bisa dibaca
di [dokumentasi database](https://rakit.esyede.my.id/docs/database/facile).



### Contoh Sederhana

Buat permission baru:

```php
$permission = new Permission();
$permission->name = 'delete_user';
$permission->save();
```

Buat role baru dengan level 7:

```php
$role = new Role();
$role->name = 'Moderator';
$role->level = 7;
$role->save();
```


Assign permission ke role:

```php
$role->permissions()->sync([$permission->id]);
```


Buat user baru:

```php
$user = new User();
$user->username = 'esyede';
$user->email = 'esyede@situsku.com';
$user->password = 'password'; // Password akan otomatis di-hash, tdak perlu hash manual.
$user->save();
```


Assign role ke user:

```php
$user->roles()->sync([$role->id]);
```


Lakukan pemeriksaan ke user:

```php
dump($user->is('Moderator')); // true
dump($user->is('Admin'));     // false


dump($user->can('delete_user')); // true
dump($user->can('add_user'));    // false


dump($user->level(7));       // true
dump($user->level(5, '<=')); // false
```


### Lisensi
Paket ini dirilis dibawah [Lisensi MIT](LICENSE)
