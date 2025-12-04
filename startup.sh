#!/bin/bash
set -euo pipefail
exec > /tmp/startup.log 2>&1

echo "Startup script iniciado: $(date)"

# Copiar configuración nginx si existe
if [ -f /home/site/ext/default ]; then
  cp /home/site/ext/default /etc/nginx/sites-enabled/ || {
    echo "Aviso: fallo al copiar default a /etc/nginx/sites-enabled/"
  }
else
  echo "No existe /home/site/ext/default"
fi

# Copiar composer.phar y hacerlo ejecutable
if [ -f /home/site/ext/composer.phar ]; then
  cp /home/site/ext/composer.phar /usr/local/bin/composer || {
    echo "Aviso: fallo al copiar composer.phar"
  }
  chmod +x /usr/local/bin/composer || echo "Aviso: chmod falló"
else
  echo "No existe /home/site/ext/composer.phar"
fi

# Instalar dependencias y ejecutar migraciones
cd /home/site/wwwroot || { echo "wwwroot no existe"; exit 0; }

# Usar composer desde /usr/local/bin si existe, si no usar composer global
COMPOSER_CMD="/usr/local/bin/composer"
if [ ! -x "$COMPOSER_CMD" ]; then
  COMPOSER_CMD="composer"
fi

echo "Ejecutando: $COMPOSER_CMD install --no-interaction --optimize-autoloader"
$COMPOSER_CMD install --no-interaction --optimize-autoloader || echo "composer install falló"

echo "Ejecutando migraciones"
php artisan migrate --force || echo "php artisan migrate falló"

# Recargar nginx de forma segura (service puede no existir)
if command -v nginx >/dev/null 2>&1; then
  nginx -s reload || echo "nginx -s reload falló"
else
  echo "nginx no encontrado"
fi

echo "Startup script finalizado: $(date)"