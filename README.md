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

## FetchNews Command
The `news:fetch` command fetches articles from various news sources based on a user-provided query and parameters. It also allows users to save queries for regular fetching.

### Command Usage
Run the command:
```bash
docker-compose exec php php artisan news:fetch
```

### Command Workflow
1. **Query Input:** Prompts for the news topic to search for.
2. **Source Selection:** Allows users to select one or more news sources from a predefined list.
3. **Save Query:** Optionally saves the query and sources to a `queries.json` file for future use.
4. **Article Limits and Dates:** Prompts for the number of articles to fetch and optional date range.
5. **Fetch Execution:** Fetches articles using the specified parameters and dispatches the `FetchNewsJob` for processing.

### Saving Queries
Saved queries are stored in `storage/app/queries.json`. If a query doesn't already exist in the file, it will be added with the associated sources.

### Example Interaction
```plaintext
Enter the news you want to search for: Laravel
Select the sources you want to search from: [NewsAPI, The Guardian]
Do you want to regularly fetch news for this query and save them to the database? (yes/no): yes
Enter the number of articles you want to fetch: 10
Enter the date from which you want to fetch the articles: 2023-01-01
Enter the date to which you want to fetch the articles: 2023-12-31
Fetching news from NewsAPI...
Fetching news from The Guardian...
```

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

