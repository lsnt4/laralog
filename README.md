# Laralog

## A Classic Laravel Database Query Logger

This package log database queries into Laravel storage logs directory. Use only for debugging purposes only.

### Installation

```
composer require --dev buddhika/laralog
```

```
php artisan vendor:publish --provider "Buddhika\Laralog\LaralogServiceProvider"
```

Laralog configuration is available in `config\laralog.php`.