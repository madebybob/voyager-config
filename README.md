# Voyager - Export


## Introduction

Voyager for Laravel is great, but it has one downside: all configuration is done within the database and cannot be configured with code. When you have multiple environments (e.g. development, staging, production), you will have conflicting configurations which is hard to sync.
This packages tries to solve this by offering the following commands:

### `artisan voyager:export`

This command will export all Voyager related tables into `.json` files into the `config` folder. Don't forget to commit this folder! :)

### `artisan voyager:import`

This command will import all data from the `config` folder into the Voyager related tables.

### `artisan voyager:clear`

Made a mistake? Want to start again? Remove all exported data with `voyager:clean`.

## Commands explained

This documentation is still to do. Try `artisan list` and `artisan voyager:export --help` for more information

## Contact

If you have suggestions or questions feel free to open up a new issue or pull request.

You can e-mail me at bob@madebybob.nl.

