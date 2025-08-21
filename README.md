# Log Viewer

A simple and secure PHP-based log viewer.
<img width="1176" height="580" alt="image" src="https://github.com/user-attachments/assets/7bcabff5-53b6-4092-8b90-0e5f24e3147e" />

# Search the whole file
<img width="892" height="569" alt="image" src="https://github.com/user-attachments/assets/ca6a8a2a-5520-4d5d-b650-3a71ed19fa3e" />

## Features

-   Securely browse log files in different folders.
-   Search for keywords within log files.
-   Modern and user-friendly interface.
-   Scans log entries for specific regular expressions or keywords (e.g., ERROR, HTTP 5\d\d, custom patterns).
-   Easy-to-edit configuration file (config.ini) to set up log paths, search patterns, and alert settings without changing code.

## Installation
To install Log Checker, follow these steps:

Clone the repository:
    ```bash
    
    git clone https://github.com/shawonsom/Log_checker.git

    cd Log_checker
    ```
Plase the Log folder in the **Log_checker** filder. 

You will able to see all the log folder or file in this location.    

## Security

The application is designed with security in mind, preventing directory traversal attacks by ensuring that file and folder paths are validated and restricted to the project's root directory.
