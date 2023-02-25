# Import large CSV file

This repository is built to present an alternative solution to an issue 
discussed in a Laracasts forum's thread: 

https://laracasts.com/discuss/channels/laravel/uploading-1-million-csv-data-extends-the-runtime-and-max-upload


## Disclaimer

As discussed in the thread linked above, there are far better approaches to this problem.

One alternative, which is much better for users and your app's stability, 
is to dispacth a job to perform the importing on the background and notify 
your user when it is done.

Another good alternative, if you are using MySQL as the forum's thread OP 
stated they are, is to use MySQL's built-in `LOAD DATA INFILE`. Note that
to use this feature you might need to change permissions on both 
your MySQL server, and on Laravel's database configuration. 

Below are two links with references for this latter approach.

- MySQL documentation on `LOAD DATA INFILE`
- About which PDO setting you might need to add to your database config: https://stackoverflow.com/a/17430025

Please refer to Laracasts forum's thread that motivated this project 
for additional insights and tips.

The approach presented in this project is using batched PDO inserts.

This project was put together for a technique demonstration purpose. It does not aim 
to be a demonstration of neither Laravel, nor PHP best practices.

Although this project uses Laravel for code organization, the import code 
is not Laravel dependent and can be used in any PHP project. 


## Relevant code

All relevant code is located on the `ImportContactsController`
available at the `./app/Http/Controllers/ImportContactsController.php` file.


## Installation and usage

```bash
git clone https://github.com/rodrigopedra/import-csv-laracasts.git
cd import-csv-laracasts
cp .env .env.example
composer install
php artisan key:generate
```

Configure your database then:

```bash
php artisan migrate
php artisan serve
```

Access the website and follow the `import` link on the home page.

There is a sample file, with 200 records at `./resources/sample.csv`

You can generate a larger sample file running:

```bash
php artisan app:generate
```

A file with 1.3 million records will be generated at `./storage/app/large-sample.csv`

On a local test, with PHP 8.1 and MySQL 8.0 installed locally, this large sample file 
was imported with an average of 20 seconds, on multiple runs.

The machine used had an Intel Core i9-9900, and was running openSUSE Tumbleweed. 
MySQL data is served from a SSD SATA drive. RAM usage is irrelevant as its barely noticeable.

Note that, when testing with the large sample file, you might need to tweak 
your local `php.ini` settings for both:

- `upload_max_filesize`
- `post_max_size`

If you are serving from `php artisan serve`, changing `max_execution_time` 
shouldn't be needed as artisan runs as a CLI process, which often is configured 
to have no upper execution limit.

If you face timeout failures, also tweak your  `max_execution_time` value 
in your `php.ini` file.


## Warning

Do not rely on the code present in this project to perform large imports on productions. 

Please refer to the disclaimer section above, and for the Laracasts forum's thread for better alternatives.
