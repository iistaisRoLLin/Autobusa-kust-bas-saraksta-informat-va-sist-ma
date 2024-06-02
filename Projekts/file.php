<?php

function processFolderFromZip($zipUrl, $databaseConnection) {
    // Fetch the contents of the zip file
    $zipContents = file_get_contents($zipUrl);

    // Check if the zip contents could be retrieved
    if (!$zipContents) {
        echo "Unable to fetch zip contents.";
        return;
    }

    // Save the zip contents to a temporary file
    $tempZipFile = tempnam(sys_get_temp_dir(), 'zip');
    file_put_contents($tempZipFile, $zipContents);

    // Open the zip file
    $zip = new ZipArchive;
    if ($zip->open($tempZipFile) === TRUE) {
        // Create a temporary directory for extracting files
        $extractedDir = sys_get_temp_dir() . '/' . uniqid('extracted_');
        mkdir($extractedDir);

        // Extract each file from the zip
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $fileName = $zip->getNameIndex($i);
            // Check if the file is a .txt file
            if (pathinfo($fileName, PATHINFO_EXTENSION) === 'txt') {
                // Extract the file contents
                $zip->extractTo($extractedDir, array($fileName));

                // Read the extracted file contents
                $extractedFilePath = $extractedDir . '/' . $fileName;

                // Prepare the table name based on the filename
                $tableName = pathinfo($fileName, PATHINFO_FILENAME);

                // Read the first line of the file to get column names and sample data
                $file = fopen($extractedFilePath, "r");
                $columns = fgetcsv($file);
                $sampleData = fgetcsv($file);
                fclose($file);

                // Generate the CREATE TABLE query
                $createTableQuery = "CREATE TABLE IF NOT EXISTS $tableName (";
                foreach ($columns as $index => $column) {
                    // Determine data type and maximum length based on sample data
                    $dataType = is_numeric($sampleData[$index]) ? 'INT' : 'VARCHAR(125)';
                    // If it's the first column, make it auto increment
                    if ($index === 0) {
                        $createTableQuery .= "`$column` $dataType AUTO_INCREMENT,";
                    } else {
                        $createTableQuery .= "`$column` $dataType,";
                    }
                }
                $createTableQuery .= "PRIMARY KEY (`{$columns[0]}`))";

                // Execute the CREATE TABLE query
                $databaseConnection->query($createTableQuery);

                // Prepare the LOAD DATA INFILE query
                $loadQuery = "LOAD DATA INFILE '" . $databaseConnection->real_escape_string($extractedFilePath) . "' 
                              IGNORE INTO TABLE $tableName 
                              FIELDS TERMINATED BY ',' ENCLOSED BY '\"' 
                              LINES TERMINATED BY '\\n' IGNORE 1 LINES";

                // Execute the LOAD DATA INFILE query
                $databaseConnection->query($loadQuery);

                // Remove the extracted file
                unlink($extractedFilePath);
            }
        }

        // Close the zip archive
        $zip->close();

        // Clean up extracted directory
        rmdir($extractedDir);
    } else {
        echo "Unable to open zip file.";
    }

    // Delete the temporary zip file
    unlink($tempZipFile);
}

// Example usage:
// Connect to your database
$databaseConnection = new mysqli('localhost', 'root', '', 'jaunie', '3307');

// Check if the connection was successful
if ($databaseConnection->connect_error) {
    die("Connection failed: " . $databaseConnection->connect_error);
}

// Call the function with the zip URL and database connection
$zipUrl = 'https://www.atd.lv/sites/default/files/GTFS/gtfs-latvia-lv.zip';
processFolderFromZip($zipUrl, $databaseConnection);

// Close the database connection
$databaseConnection->close();


?>