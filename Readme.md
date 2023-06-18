# MintBerry Documentation

## Introduction

This documentation provides an overview and guide for using the MintBerry Framework. This framework is designed to facilitate the development of web applications by following the MVC architectural pattern. It provides a modular and extensible structure for building maintainable PHP applications.

## Table of Contents

- [Getting Started](#getting-started)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Routing](#routing)
- [Controllers](#controllers)
- [Models](#models)
- [Views](#views)
- [Middlewares](#middlewares)
- [Database](#database)
- [Authentication](#authentication)
- [Validation](#validation)
- [Error Handling](#error-handling)
- [Testing](#testing)
- [Deployment](#deployment)

## Getting Started

To get started with the PHP MVC Framework, follow the steps below.

## Requirements

Ensure that your system meets the following requirements before installing the PHP MVC Framework:

- PHP 7.4 or later
- Apache or Nginx web server
- Composer

## Installation

To install the MintBerry Framework, perform the following steps:

1. Clone the repository:

`git clone git@github.com:mahfuzurrahman98/MintBerry.git`

2. Change to the project directory:

`cd MintBerry`

3. Install the project dependencies using Composer:

`composer install`

4. Hit the below command to start the development server.

`composer run dev-server`

5. To run the project on shared server(Apache2 only), go to `.env` and edit the `APP_ROOT`

`APP_ROOT=folder_name`

## Configuration

The configuration files for the PHP MVC Framework can be found in the `config` directory. Adjust the settings in these files according to your application's requirements.

## Routing

The routing in the PHP MVC Framework is handled by the `Router` class. Routes can be defined in the `/src/App/routes.php` file. Here's an example of how to define routes:

`use App\Controllers\HomeController;  `

`$router->get('/', HomeController::class, 'index');  $router->get('/about', HomeController::class, 'about');  $router->post('/contact', HomeController::class, 'contact');`

## Controllers

Controllers in the PHP MVC Framework handle the logic and data flow of your application. They interact with models to retrieve and manipulate data, and render views to display the final output. Controllers should be placed in the `app/Controllers` directory.

## Models

Models represent the data and business logic of your application. They interact with the database and provide methods for data manipulation and retrieval. Models should be placed in the `app/Models` directory.

## Views

Views in the PHP MVC Framework handle the presentation logic of your application. They are responsible for displaying data to the user. Views should be placed in the `app/Views` directory.

## Middlewares

Middlewares in the PHP MVC Framework allow you to filter and modify HTTP requests and responses. They provide a convenient way to implement cross-cutting concerns, such as authentication, authorization, and input validation. Middlewares should be placed in the `app/Middlewares` directory.

## Database

The PHP MVC Framework supports various database systems through the use of database drivers. The database configuration can be found in the `config/database.php` file. Use the appropriate database driver and configure the connection settings accordingly.

## Authentication

Authentication in the PHP MVC Framework can be implemented using middleware or dedicated authentication libraries. You can define authentication routes, handle user registration and login, and manage user sessions.

## Validation

Input validation is an important aspect of web application development. The PHP MVC Framework provides validation features that help ensure the integrity and security of user input. You can define validation rules for your form inputs and easily validate user-submitted data.

## Error Handling

Error handling in the PHP MVC Framework is centralized in the `ErrorHandler` class. You can customize error handling and logging by modifying the `app/Handlers/ErrorHandler.php` file.

## Deployment

To deploy your PHP MVC Framework application, ensure that the server meets the framework's requirements. Configure the web server to point to the `public` directory of your application. Set the appropriate file permissions and ensure that the necessary dependencies are installed.
