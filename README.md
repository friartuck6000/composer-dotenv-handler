# Dotenv Handler for Composer

This script works like [Incenteev's ParameterHandler](https://github.com/Incenteev/ParameterHandler), allowing you to
manage your [Dotenv](https://github.com/vlucas/phpdotenv) configuration interactively during a Composer install or
update.

## Installation

```
composer install friartuck6000/composer-dotenv-handler
```

## Usage

Add the following to your `post-install-cmd` and `post-update-cmd` script hooks in your composer.json:

```
"Ft6k\\ComposerDotenv\\ScriptHandler::buildParameters"
```
