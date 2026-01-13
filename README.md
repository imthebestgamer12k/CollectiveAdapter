\# =============LARAVEL COLLECTIVE ADAPTER==================


\## A compatibility solution for migrating old Laravel collective to new Laravel version

BRIEF DECRIPTION OF THE PROJECT:
This project was mainly created for Laravel artisans who are looking for solutions/alternative on re-using Laravel Collective in Laravel blades. 


\## Installation

STEPS TO INSTALL THIS PRJECT:
1. Open terminal in your project directory. (or use Git Bash)
2. Run composer require niel/collective-adapter
3. Run composer dump-autoload
4. Check the newly installed collective-adapter package in your vendor folder


\## Usage

HOW TO USE THE PACKAGE:
1. To use this package, go to your App/Providers directory. Edit the AppServiceProvider
2. Under your namespace add "use Niel\CollectiveAdapter\Services\FormService;"
3. Bind FormService to the global 'form' facade helper and put this code inside the register() function:
   
        $this->app->bind('form', function ($app) {
            return new FormService();
        });



\## Technologies Used

\- Git
\- Composer
\- Php 7.2.* (For Laravel v 7+
\- PHP 8.1.* (For Laravel v 9+ & above)



\## AUTHOR/CREATED BY:

Lien Yabis(Niel Sibay)



