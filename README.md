# Prosperous Universe Community Report

This is an unofficial economic report for [Prosperous Universe](https://prosperousuniverse.com/), made by the community,
with data from the [FNAR FIO API](https://doc.fnar.net/).

> [!NOTE]
> This project is still in development. The code, design and features can change drastically.

## Development

### Stack

- **PHP 8.2**
- **Symfony 7.2**, which includes i. a.:
    - **[Twig](https://twig.symfony.com/)** for templating
    - **[Doctrine](https://www.doctrine-project.org/)** for database object management
    - **[AssetMapper](https://www.doctrine-project.org/)** for asset management
    - **[Stimulus](https://ux.symfony.com/stimulus)** for interactive components
    - **[Chart.js](https://symfony.com/bundles/ux-chartjs/current/index.html)** for the graphs

### Installation

You'll need to have PHP and [Composer](https://getcomposer.org/) installed.

After cloning the repository:

1. Run `composer install` to install the dependencies
2. Run `php bin/console doctrine:migrations:migrate` to setup the local database (SQLite).
3. Run `php bin/console fio` to see all available import commands from FIO. Use `php bin/console fio:sync` to queue up a
   full import. This can take a few hours. The command will only queue up the required commands. To actually import the
   data, you have to
   [start a message consumer](https://symfony.com/doc/current/messenger.html#consuming-messages-running-the-worker):<br>
   `php bin/console messenger:consume command -l 1000`<br>
   The consumer will stop after 1,000 commands to prevent memory runouts. You will need to restart it a few times.
4. Run `php bin/console server:run` to start the local web server. After that, you can access the application at
   `http://localhost:8000`.

### Testing

Testing will be done with PHPUnit, but isn't implemented yet. All tests are manually at the moment.

### Contribution

Contributions are welcome! Please review the issues for current tasks and projects and feel free to open a new issue or
pull request.
