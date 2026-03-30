# Use official PHP image (Debian-based for better compatibility)
FROM php:8.2-fpm-bullseye

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl \
    wget \
    git \
    bash \
    libc-dev \
    libonig-dev \
    libzip-dev \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    python3 \
    make \
    g++ \
    ca-certificates \
    gnupg \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js from NodeSource (latest LTS)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install \
    pdo \
    pdo_mysql \
    mysqli \
    mbstring \
    zip \
    gd \
    exif \
    pcntl \
    intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project files (excluding vendor, node_modules via .dockerignore)
COPY . .

# Install PHP dependencies (composer will download vendor folder)
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Setup npm cache directory
RUN mkdir -p /tmp/npm-cache && \
    npm config set cache --global /tmp/npm-cache

# Install Node dependencies for Tailwind
RUN npm install --production

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Create writable directory structure with proper permissions
RUN mkdir -p /var/www/html/writable/cache && \
    mkdir -p /var/www/html/writable/logs && \
    mkdir -p /var/www/html/writable/session && \
    mkdir -p /var/www/html/writable/uploads && \
    mkdir -p /var/www/html/writable/debugbar

# Set permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 777 /var/www/html/writable

# Expose port
EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]
CMD ["php-fpm"]
