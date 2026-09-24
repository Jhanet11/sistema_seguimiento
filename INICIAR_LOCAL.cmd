@echo off
setlocal
cd /d "%~dp0"
set "EDESSI_PHP=php"
if exist "C:\xampp\php\php.exe" set "EDESSI_PHP=C:\xampp\php\php.exe"
if not exist "vendor\autoload.php" (
  echo Faltan dependencias. Sigue la instalacion del archivo README.md.
  pause
  exit /b 1
)
if not exist ".env" (
  echo Falta configurar .env. Sigue el archivo README.md.
  pause
  exit /b 1
)
echo EDESSI - http://127.0.0.1:8765
echo Mantenga esta ventana abierta. Para detener el servidor, presione Ctrl+C.
cd public
"%EDESSI_PHP%" -d extension=gd -S 127.0.0.1:8765 "../vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php"
pause
