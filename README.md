=>  Local Development Setup Guide

This guide will help you set up the addweb_practical Laravel project in your local development environment.

Requirements
	Ensure you have the following installed:

	PHP >= 8.0
	Composer (PHP dependency manager)
	MySQL or any other database server
	Node.js & NPM
	Git
Step 1: Clone the Repository
	First, clone the repository from GitHub:
	
	git clone https://github.com/niravc46/AddWebPractical.git
	Navigate into the project directory:
	cd AddWebPractical
Step 2: Install PHP Dependencies
		Run the following command to install all PHP dependencies via Composer: 
  		
	composer install

Step 3: Configure Environment File
	Create a copy of the .env.example file and rename it to .env:
	
	cp .env.example .env
	
Now, open the .env file in a text editor and set the following environment variables:
	
.env
	
		APP_NAME=addweb_practical
		APP_ENV=local
		APP_KEY=base64:generated_key
		APP_DEBUG=true
		APP_URL=http://localhost
		
		DB_CONNECTION=mysql
		DB_HOST=127.0.0.1
		DB_PORT=3306
		DB_DATABASE=addweb_practical
		DB_USERNAME=your_username
		DB_PASSWORD=your_password
Configure below things in .env for mail notification

        MAIL_MAILER=smtp
        MAIL_HOST=sandbox.smtp.mailtrap.io
        MAIL_PORT=2525
        MAIL_USERNAME=username
        MAIL_PASSWORD=password
        MAIL_ENCRYPTION=null
        MAIL_FROM_ADDRESS="noreply@addweb.com"
        MAIL_FROM_NAME="${APP_NAME}"

Step 4: Generate Application Key
	Generate the Laravel application key:
	
	php artisan key:generate

Step 5: Set Up the Database
	Create a MySQL database named addweb_practical (or use a different name and adjust the .env file accordingly).
	
	Run the migrations to set up the database schema:
	
	php artisan migrate
	
Seed roles and permissions:
	
	php artisan db:seed

Step 6: Install Node Dependencies

Install all frontend dependencies using NPM:
	
	npm install

Step 7: Compile Assets

	For development, run:
	npm run dev
	For production, run:
	npm run prod

Step 8: Run the Local Development Server
	You can now start the Laravel development server:
 
	php artisan serve

The server should now be running at 

	http://localhost:8000.

For Admin login 

	username: admin@admin.com
	password: password

For author login

	Autor 1
		username: author1@author.com
		password: password
	Autor 2
		username: author2@author.com
		password: password
	Autor 3
		username: author3@author.com
		password: password
