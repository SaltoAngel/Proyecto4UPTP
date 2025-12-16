@echo off
setlocal enableextensions enabledelayedexpansion
REM ------------------------------------------------------------------
REM  jasperstarter8.bat - Wrapper para ejecutar JasperStarter con Java 8
REM ------------------------------------------------------------------
REM Requisitos:
REM  - Definir JAVA_HOME_JASPER apuntando a tu JDK/JRE 8 (x64).
REM  - (Opcional) Definir JASPERSTARTER_EXE con la ruta a jasperstarter.exe.
REM    Si no se define, usará C:\Tools\jasperstarter\bin\jasperstarter.exe
REM  - Este wrapper no cambia tu Java del sistema; solo para este proceso.

REM 1) Resolver JAVA_HOME_JASPER (usar existente o auto-detectar instalaciones comunes)
if not defined JAVA_HOME_JASPER (
  for /f "delims=" %%d in ('dir /b /ad "C:\\Program Files\\Eclipse Adoptium\\jdk-8*" 2^>nul') do (
    set "JAVA_HOME_JASPER=C:\Program Files\Eclipse Adoptium\%%d"
    goto gotJ8
  )
  for /f "delims=" %%d in ('dir /b /ad "C:\\Program Files\\Java\\jdk1.8*" 2^>nul') do (
    set "JAVA_HOME_JASPER=C:\Program Files\Java\%%d"
    goto gotJ8
  )
)
:gotJ8
if defined JAVA_HOME_JASPER (
  set "JAVA_HOME=%JAVA_HOME_JASPER%"
  set "PATH=%JAVA_HOME%\bin;%PATH%"
) else (
  echo [ERROR] No se encontro JAVA_HOME_JASPER ni una instalacion de Java 8.
  echo         Instale JDK/JRE 8 (x64) o defina JAVA_HOME_JASPER.
  endlocal & exit /b 1
)

REM 2) Resolver ruta de JasperStarter
if defined JASPERSTARTER_EXE (
  set "JS=%JASPERSTARTER_EXE%"
) else (
  set "JS=C:\Program Files (x86)\JasperStarter\bin\jasperstarter.exe"
)

if not exist "%JS%" (
  echo [ERROR] jasperstarter.exe no encontrado en: "%JS%"
  echo Configure la variable de entorno JASPERSTARTER_EXE con la ruta completa.
  echo Ejemplo: setx JASPERSTARTER_EXE "C:\Tools\jasperstarter\bin\jasperstarter.exe" /M
  endlocal & exit /b 1
)

REM 3) Comprobación rápida de Java activo
set "JAVA_LINE="
for /f "delims=" %%v in ('java -version 2^>^&1 ^| findstr /i "version"') do set "JAVA_LINE=%%v"
set "JAVA_VER="
for /f "tokens=3 delims= " %%v in ("%JAVA_LINE%") do set "JAVA_VER=%%v"
set "JAVA_VER=%JAVA_VER:\"=%"
if not defined JAVA_VER (
  echo [ERROR] No se pudo determinar la version de Java. Revise JAVA_HOME_JASPER.
  endlocal & exit /b 1
)
echo [INFO] Java activo: %JAVA_VER%
echo %JAVA_VER% | findstr /C:"1.8." >nul
if errorlevel 1 (
  echo [ERROR] JasperStarter requiere Java 1.8.x. Detectado: %JAVA_VER%
  echo         Instale JDK/JRE 8 (x64) y ajuste JAVA_HOME_JASPER.
  endlocal & exit /b 1
)

REM 4) Ejecutar JasperStarter con los mismos argumentos
"%JS%" %*
set EXITCODE=%ERRORLEVEL%

endlocal & exit /b %EXITCODE%
