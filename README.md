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

## Fetch News Command

The application includes a custom Artisan command to fetch news articles from various sources. The command interacts with the user to collect inputs like query, sources, number of articles, and date range. It then fetches the articles and stores them in the database.

### Command Details

#### Command Signature:
```bash
php artisan news:fetch
```

#### Workflow:
1. Prompts the user to input the following:
   - **Search Query**: The term to search for in news articles.
   - **Sources**: Select one or more sources from predefined options (e.g., NewsAPI, The Guardian).
   - **Number of Articles**: Specify the number of articles to fetch (e.g., 10).
   - **From Date**: (Optional) Start date for fetching articles (format: YYYY-MM-DD).
   - **To Date**: (Optional) End date for fetching articles (format: YYYY-MM-DD).
2. Fetches news articles from the selected sources using the query and other parameters.
3. Stores the fetched articles in the database, creating related authors and categories as needed.
4. Displays progress and notifies the user if no articles are found.

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

