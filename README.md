# PHP Bing Search Scraper

This PHP script provides a simple way to scrape search results from Bing. It's designed to be easy to use and customize for various web scraping needs.

## Features

- Scrape organic search results from Bing
- Customizable number of results
- Proxy support
- Retry mechanism for failed requests
- Detailed result information including position, source, title, link, and summary

## Requirements

- PHP 7.0 or higher
- DOM extension enabled

## Installation

1. Clone this repository:
   ```
   git clone https://github.com/yourusername/webscrape-bing-serp-results.git
   ```
2. Navigate to the project directory:
   ```
   cd webscrape-bing-serp-results
   ```

## Usage

Here's a basic example of how to use the BingScraper:
php
<?php
require_once 'BingScraper.php';
$scraper = new BingScraper();
$results = $scraper->get_results("PHP programming");
echo json_encode($results, JSON_PRETTY_PRINT);
?>


## Configuration

You can customize the scraper by passing parameters to the constructor:


php
$scraper = new BingScraper(
$headers, // Custom headers (optional)
$max_retries, // Maximum number of retries (default: 3)
$num_results, // Number of results to fetch (default: 10)
$proxy // Proxy URL (optional)
);


## Output

The scraper returns an array with the following structure:

- `query`: The search URL
- `organic_results`: An array of search results, each containing:
  - `position`: The position of the result
  - `source`: The source of the result
  - `title`: The title of the result
  - `link`: The URL of the result
  - `summary`: A brief summary of the result
- `number_of_results`: The total number of results found
- `status`: The HTTP status of the request

## Disclaimer

This scraper is for educational purposes only. Be sure to review Bing's terms of service before using this in any project. The developers are not responsible for any misuse of this script.

## Contributing

Contributions, issues, and feature requests are welcome. Feel free to check [issues page](https://github.com/yourusername/php-bing-scraper/issues) if you want to contribute.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
