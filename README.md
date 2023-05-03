# real-time api chat

This is a real-time chat application built with Laravel  that allows users to send and receive messages in real-time. The application uses the Pusher service to broadcast chat messages to connected clients.

## Prerequisites

* Before running this application, you need to have the following installed on your machine:
* PHP 7.3 or higher
* MySQL or any other database system
* Composer
* PHP 7.3 or higher
* MySQL or any other database system

## Run Locally

Clone the repository to your local machine using the following command:
```shell
git clone https://github.com/hossamsoliuman/real-time-chat.git
cd real-time-chat
```

Generate .env file
```shell
cp .env.example .env
```

Then, configure the .env file according to your use case.

Install the dependencies and then compile the assets
```shell
composer install
npm install
npm run dev
```

Populate the tables to the database.
Create a new database for the project and run migration to create the necessary tables:
```shell
php artisan migrate
```

Optional: Seed data to the dabase
```shell
php aritsan db:seed
```

Generate app key
```shell
php artisan key:generate
```

Run the application
```shell
php artisan serve
```

Access the application in your web browser at http://localhost:8000.

## User Logins

- User: email: user@gmail.com, password: user@gmail.com.

## Note

I developed this project out of my head, not on YouTube