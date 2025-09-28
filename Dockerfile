FROM php:8.2-cli

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project files
COPY . .

# Install dependencies if composer.json exists
RUN if [ -f composer.json ]; then composer install; fi

# Expose port
EXPOSE 10000

# Start the PHP built-in server
CMD ["php", "-S", "0.0.0.0:10000", "index.php"]