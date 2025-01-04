# News Aggregator API

## Overview
This project is a Laravel-based RESTful API for a news aggregator service. The API provides endpoints for managing users, fetching articles, and personalizing news feeds based on user preferences. The application uses data aggregated from multiple news sources and supports features like pagination, search, and user-specific preferences.

## Getting Started

### Prerequisites
- Docker and Docker Compose installed on your machine.
- API keys for at least three of the following news sources:
  - NewsAPI
  - The Guardian
  - New York Times

### Setup Instructions

1. **Clone the Repository**
   ```bash
   git clone https://github.com/Kaynite/innoscripta-assignment.git news-aggregator
   cd news-aggregator
   ```

2. **Environment Configuration**
   Copy the example environment file and update the configuration:
   ```bash
   cp .env.example .env
   ```
   Update the `.env` file with your database credentials, API keys, and other settings.

3. **Build and Start the Application**
   Use Docker Compose to build and start the application:
   ```bash
   docker-compose up -d --build
   ```

4. **Access the Application**
   - API Base URL: `http://localhost`
   - API Documentation: `http://localhost/docs`

## Testing
- Run the test suite using PestPHP:
  ```bash
  docker compose exec php php artisan test
  ```
- Tests include both unit and feature tests to ensure API endpoints and core functionalities work as expected.

## License
This project is open-source and available under the [MIT License](LICENSE).

## Contact
For any questions or issues, please contact:
- Email: ksaber276@gmail.com
- GitHub: [Kaynite](https://github.com/kaynite)
