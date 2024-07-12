# Event Management Application

This is an Event Management application built using the Symfony framework. The application allows users to create
events,
register for events, and view a list of events. Additionally, there is a console command to list all registered users.

## Key Technologies

- **Symfony **: The PHP framework used for building the application.
- **Doctrine ORM**: Used for database interactions.
- **Twig**: Template engine used for rendering views.
- **Tailwind CSS**: CSS framework used for styling.
- **Docker**: Containerization platform used for development and deployment.

## Setup Instructions

### Prerequisites

- Docker
- Docker Compose

### Installation

1. **Clone the Repository**

    ```bash
    git clone https://github.com/zed0rb/event-management-app.git
    cd event-management-app
    ```

2. **Configure Environment Variables**

   Create a `.env` file at the root of the project and set up your database connection:

    ```dotenv
    DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"
    ```

3. **Build and Start the Docker Containers**

    ```bash
    docker-compose up -d --build
    ```

   This command will build the Docker images and start the containers in detached mode.

4. **Set Up the Database**

   Create the database and run migrations:

    ```bash
    docker-compose exec web php bin/console doctrine:database:create
    docker-compose exec web php bin/console doctrine:migrations:migrate

## Application Features

- **Event Creation**: Create new events with a name, date, and registration limit.
- **User Registration**: Register users for events, ensuring users cannot register for the same event multiple times.
- **Event Listing**: View a list of all events.
- **Console Command**: Output a list of registered users with their emails in the console.

## Usage

### Viewing the Event List

- Navigate to `http://localhost:8080/index.php` to see a list of all events.

### Creating an Event

1. Navigate to `http://localhost:8080/index.php/event/create`.
2. Fill out the form with the event details.
3. Click the "Submit event" button.

### Registering for an Event

1. Navigate to the event list at `http://localhost:8080/index.php`.
2. Click on the "Register" button next to the desired event.
3. Fill out the registration form.
4. Click the "Register" button.

## Running the Console Command

To list all registered users with their emails:

1. Open your terminal.
2. Navigate to the root of the project.
3. Run the following command:

    ```bash
    docker-compose exec web php bin/console app:registered-users
    ```

   This will output a table of all registered users and their emails.

