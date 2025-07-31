<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log File Viewer</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 900px; margin: auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 15px rgba(0,0,0,0.05); }
        h1 { text-align: center; color: #0056b3; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; }
        select, input[type="text"] { width: 100%; padding: 12px; border-radius: 6px; border: 1px solid #ced4da; box-sizing: border-box; transition: border-color 0.2s; }
        select:focus, input[type="text"]:focus { border-color: #80bdff; outline: none; }
        button { width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; transition: background-color 0.2s; }
        button:hover { background-color: #0056b3; }
        #results { margin-top: 25px; padding: 15px; border: 1px solid #e9ecef; border-radius: 6px; background: #f8f9fa; min-height: 150px; white-space: pre-wrap; font-family: "Courier New", Courier, monospace; }
        .footer { text-align: center; margin-top: 20px; font-size: 14px; color: #6c757d; }
    </style>
</head>
<body>

<div class="container">
    <h1>Log File Viewer</h1>
    <div class="form-group">
        <label for="folder-select">Select Folder:</label>
        <select id="folder-select"></select>
    </div>
    <div class="form-group">
        <label for="file-select">Select Log File:</label>
        <select id="file-select"></select>
    </div>
    <div class="form-group">
        <label for="search-keyword">Search Keyword (optional):</label>
        <input type="text" id="search-keyword" placeholder="Leave blank to view the whole file...">
    </div>
    <button id="search-button">Search / View</button>
    <div id="results">
        <p>Results will be displayed here.</p>
    </div>
</div>

<footer class="footer">
    <p>Log Viewer &copy; 2025</p>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const folderSelect = document.getElementById('folder-select');
        const fileSelect = document.getElementById('file-select');
        const searchButton = document.getElementById('search-button');
        const searchKeyword = document.getElementById('search-keyword');
        const resultsDiv = document.getElementById('results');

        // Fetch folder list on page load
        fetch('scan_folders.php')
            .then(response => response.json())
            .then(folders => {
                folders.forEach(folder => {
                    const option = document.createElement('option');
                    option.value = folder.path;
                    option.textContent = folder.name;
                    folderSelect.appendChild(option);
                });
                // Load files for the default selected folder
                loadFiles(folderSelect.value);
            });

        // Handle folder selection change
        folderSelect.addEventListener('change', function() {
            loadFiles(this.value);
        });

        function loadFiles(folder) {
            fileSelect.innerHTML = ''; // Clear existing file options
            fetch(`scan.php?folder=${encodeURIComponent(folder)}`)
                .then(response => response.json())
                .then(files => {
                    if (files.error) {
                        resultsDiv.textContent = `Error: ${files.error}`;
                        return;
                    }
                    if (files.length === 0) {
                        fileSelect.innerHTML = '<option>No log files found</option>';
                        return;
                    }
                    files.forEach(file => {
                        const option = document.createElement('option');
                        option.value = file;
                        option.textContent = file;
                        fileSelect.appendChild(option);
                    });
                });
        }

        // Handle search button click
        searchButton.addEventListener('click', function() {
            const selectedFolder = folderSelect.value;
            const selectedFile = fileSelect.value;
            const keyword = searchKeyword.value;

            if (!selectedFile || selectedFile === 'No log files found') {
                alert('Please select a valid log file.');
                return;
            }

            resultsDiv.textContent = 'Loading...';

            fetch('search.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `folder=${encodeURIComponent(selectedFolder)}&file=${encodeURIComponent(selectedFile)}&keyword=${encodeURIComponent(keyword)}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(data => {
                resultsDiv.textContent = data.trim() ? data : 'No results found or file is empty.';
            })
            .catch(error => {
                console.error('Error:', error);
                resultsDiv.textContent = 'An error occurred. Check the console for details.';
            });
        });
    });
</script>

</body>
</html>
