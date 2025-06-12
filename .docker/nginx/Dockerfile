# .docker/nginx/Dockerfile.prod

# Używamy tego samego obrazu bazowego PHP, aby mieć dostęp do jego etapów budowania
FROM php:8.2-fpm-bookworm as php_build

# Używamy etapu 'frontend' z Twojego Dockerfile.prod dla PHP jako źródła plików
# Musimy odtworzyć kroki prowadzące do etapu 'frontend'
WORKDIR /var/www/symfony
COPY package.json package-lock.json ./
RUN npm install
COPY . .
RUN npm run build

# --- Finalny obraz Nginx ---
FROM nginx:1.21-alpine

# Kopiujemy konfigurację Nginx
COPY .docker/nginx/nginx.conf  /etc/nginx/
COPY .docker/nginx/templates /etc/nginx/templates/
RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf

# ✅ Kopiujemy ZBUDOWANE assety i pliki publiczne z etapu 'php_build'
COPY --from=php_build /var/www/symfony/public /var/www/symfony/public

EXPOSE 80