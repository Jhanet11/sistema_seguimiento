# EDESSI · Sistema de seguimiento y reparación

Aplicación Laravel para registrar clientes y equipos, gestionar órdenes de servicio y consultar su avance. La versión corregida incorpora los requisitos del documento del proyecto y una interfaz renovada.

## Abrir la copia preparada

La carpeta de entrega `outputs/sistema_seguimiento` incluye las dependencias, los recursos compilados y una base SQLite de demostración, independiente del proyecto original.

1. Ejecuta `INICIAR_LOCAL.cmd` dentro de esa carpeta si el servidor no está iniciado.
2. Abre **http://127.0.0.1:8765**.
3. Usa una cuenta de demostración:

| Rol | Correo o C.I. | Contraseña de demostración |
| --- | --- | --- |
| Administrador | admin@edessi.com | DemoEdessi2026! |
| Técnico | carlos@edessi.com | DemoEdessi2026! |
| Cliente | 8451236 | DemoEdessi2026! |

Estas cuentas son solo para la copia local de demostración. No deben utilizarse con datos reales. El servidor local se limita a esta computadora; los QR de esta demo apuntan a `127.0.0.1` y no se abren desde otro dispositivo.

## Instalación desde el ZIP o una carpeta sin dependencias

Requisitos: PHP 8.2 o superior; extensiones PDO, pdo_mysql o pdo_sqlite, mbstring, XML, DOM, fileinfo y GD; Composer. ZIP facilita la instalación. Para volver a compilar la interfaz: Node.js 22.12+ y npm. Los recursos compilados se incluyen en `public/build`.

En XAMPP, habilita `extension=gd` y `extension=zip` en el `php.ini` que indica `php --ini`. El iniciador local agrega GD solo a su proceso, sin cambiar la configuración global.

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
```

Configura `.env` antes de migrar. Para SQLite, deja `DB_CONNECTION=sqlite` y crea una base vacía **solo si no existe**:

```powershell
if (!(Test-Path database/database.sqlite)) { New-Item database/database.sqlite -ItemType File }
php artisan migrate
php artisan edessi:admin
```

`edessi:admin` solicita nombre, correo y contraseña. `db:seed` no crea cuentas con contraseñas predeterminadas. La demo optativa se instala con `php artisan db:seed --class=DemoSeeder`, únicamente en entorno `local` sin usuarios.

Para MariaDB usa en `.env` los datos reales de una base previamente creada:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=edessi
DB_USERNAME=usuario_de_la_base
DB_PASSWORD=contraseña_de_la_base
```

Después ejecuta `php artisan migrate` y `php artisan edessi:admin`. Para arrancar usa `INICIAR_LOCAL.cmd` o `php artisan serve` si PHP ya tiene habilitado GD. En el segundo caso, el puerto predeterminado es 8000.

## Actualizar una instalación existente

Respalda la base de datos y conserva su `.env` y `APP_KEY`. Copia los archivos corregidos y `public/build`; luego ejecuta:

```powershell
composer install
php artisan optimize:clear
php artisan migrate
```

No uses `migrate:fresh` ni `DemoSeeder` sobre una base con registros reales. La nueva migración agrega campos, índices, códigos de seguimiento e historial de estados; no elimina registros. Los cambios de estado anteriores a esta actualización no se reconstruyen artificialmente.

## Interfaz y pruebas

```powershell
npm ci
npm run build
php artisan test
```

La suite usa SQLite en memoria por defecto. También se ejecutó sobre una base MariaDB exclusiva de verificación. Nunca apuntes PHPUnit a una base con información real, ya que usa `RefreshDatabase`.

Para mantener el formato PHP: `php vendor/bin/pint --dirty`.

## Correo y publicación

Por defecto `MAIL_MAILER=log` registra correos sin enviarlos. Para enviarlos configura las variables `MAIL_*` de tu proveedor SMTP y mantén el proceso de cola:

```powershell
php artisan queue:work --tries=3
```

El aviso web se guarda de inmediato y el correo se encola. También se encola el correo para clientes con dirección de contacto aunque no tengan cuenta.

En un servidor real, configura `APP_ENV=production`, `APP_DEBUG=false`, `APP_DEMO=false`, HTTPS y `APP_URL` con la dirección pública correcta; el directorio web debe ser `public`. No se ha publicado el sistema en Internet ni configurado un SMTP externo.

Consulta `CAMBIOS_Y_PRUEBAS.md` para ver las correcciones y las decisiones de permisos basadas en la documentación.
