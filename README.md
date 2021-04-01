<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.
## Installing Laravel

<p>Laravel utilizes <a href="https://getcomposer.org">Composer</a> to manage its dependencies. So, before using Laravel, make sure you have Composer installed on your machine.</p>

<p>Download the Laravel installer using Composer:</p>

<pre>composer global require laravel/installer</pre>

## About Project

<p>This is the example of laravel backend api for general store. The main features of this project are:</p>

- Users with authentication ability
- Products with custom properties
- Stores  with custom properties
- Store managers
- Orders

## Installation Guide

<h3>1. Clone GitHub repo for this project locally</h3>
<p>If the project is hosted on github, we can use git on your local computer to clone it from github onto your local computer.</p>
<p><strong>Note: </strong>Make sure you have git installed locally on your computer first.</p>
<p>Find a location on your computer where you want to store the project. Run the following command on that location by executing this command.</p>
<pre>git clone git@github.com:zullum/laravel_store_api.git</pre>

<p>Once this runs, you will have a copy of the project on your computer.</p>
<h3>2. cd into your project</h3>
<p>You will need to be inside that project file to enter all of the rest of the commands in this tutorial. Next step is to move your terminal working location to the project file we just barely created. </p>
<pre>cd laravel_store_api</pre>

<h3>3. Install Composer Dependencies</h3>
<p>Whenever you clone a new Laravel project you must now install all of the project dependencies. This is what actually installs Laravel itself, among other necessary packages to get started.</p>
<p>When we run composer, it checks the <code>composer.json</code> file which is submitted to the github repo and lists all of the composer (PHP) packages that your repo requires. Because these packages are constantly changing, the source code is generally not submitted to github, but instead we let composer handle these updates. So to install all this source code we run composer with the following command.</p>
<pre>composer install</pre>
<h3>4. Install NPM Dependencies</h3>
<p>Just like how we must install composer packages to move forward, we must also install necessary NPM packages to move forward. This will install all Javascript (or Node) packages required. The list of packages that a repo requires is listed in the <code>packages.json</code> file which is submitted to the github repo. Just like in step 3, we do not commit the source code for these packages to version control (github) and instead we let NPM handle it.</p>
<pre>npm install</pre>
<h3>5. Create a copy of your .env file</h3>
<p><code>.env</code> files are not generally committed to source control for security reasons. But there is a <code>.env.example</code> which is a template of the <code>.env</code> file that the project expects us to have. So we will make a copy of the <code>.env.example</code> file and create a <code>.env</code> file that we can start to fill out to do things like database configuration in the next few steps.</p>
<pre>cp .env.example .env</pre>
<p>This will create a copy of the <code>.env.example</code> file in your project and name the copy simply <code>.env</code>.</p>
<h3>6. Generate an app encryption key</h3>
<p>Laravel requires you to have an app encryption key which is generally randomly generated and stored in your <code>.env</code> file. The app will use this encryption key to encode various elements of your application from cookies to password hashes and more.</p>
<p>Laravel’s command line tools thankfully make it super easy to generate this. In the terminal we can run this command to generate that key. (Make sure that you have already installed Laravel via composer and created an .env file before doing this, of which we have done both).</p>
<pre>php artisan key:generate</pre>
<p>If you check the <code>.env</code> file again, you will see that it now has a long random string of characters in the <code>APP_KEY</code> field. We now have a valid app encryption key.</p>
<h3>7. Create an empty database for our application</h3>
<p>Create an empty database for your project using the database tools you prefer. In our example we created a database called “laravel_store_api”. Just create an empty database here, the exact steps will depend on your system setup. If you choose different name just make sure to set that name in the next step.</p>
<h3>8. In the .env file, add database information to allow Laravel to connect to the database</h3>
<p>We will want to allow Laravel to connect to the database that you just created in the previous step. To do this, we must add the connection credentials in the .env file and Laravel will handle the connection from there.</p>
<p>In the .env file fill in the <code>DB_HOST</code>, <code>DB_PORT</code>, <code>DB_DATABASE</code>, <code>DB_USERNAME</code>, and <code>DB_PASSWORD</code> options to match the credentials of the database you just created. This will allow us to run migrations and seed the database in the next step.</p>

<h3>9. Migrate the database</h3>
<p>Once your credentials are in the .env file, now you can migrate your database.</p>
<pre>php artisan migrate</pre>
<p>It’s not a bad idea to check your database to make sure everything migrated the way you expected.</p>
<h3>10. Generate passport client keys for authentication</h3>
<p>Next, you should execute the <code>passport:install</code> Artisan command. This command will create the encryption keys needed to generate secure access tokens. In addition, the command will create "personal access" and "password grant" clients which will be used to generate access tokens:</p>
<pre>php artisan passport:install</pre>
<h3>11. Seed the database</h3>
<p>After the migrations are complete and you have the database structure required, then you can seed the database (which means add dummy data to it).</p>
<pre>php artisan db:seed</pre>
<p>Create additional starter dummy data in this exact order since they depend on each other.</p>
<pre>php artisan db:seed --class=UserSeeder</pre>
<pre>php artisan db:seed --class=AdminUserSeeder</pre>
<pre>php artisan db:seed --class=StoreSeeder</pre>
<pre>php artisan db:seed --class=ProductSeeder</pre>

<h3>12. Run the project</h3>
<p>Execute the following commands to run this project:</p>
<pre>php artisan serve</pre>
<p>Open <strong>another terminal</strong> and run the following command to run the queues.</p>
<pre>php artisan queue:work</pre>
<hr>
<h2>Wrapping Up</h2>
<p>That is all you need to do to run this project in your environment.</p>

## QA and Validation Documentation
- [Google Slides Document with Validation](https://docs.google.com/presentation/d/16qASeuFcIJ7-txEtz1q49e2ED4F7nQMplgIhKmdCYKQ/edit#slide=id.p).
