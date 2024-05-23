<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Restaurant Recommendations</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <!-- Sidebar content -->
        </div>
        <div class="main-content">
            <div class="messages" id="messages">
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Get the form inputs
                    $location = htmlspecialchars($_POST['location']);
                    $cuisine = htmlspecialchars($_POST['cuisine']);
                    $diet = htmlspecialchars($_POST['diet']);

                    // Construct the query
                    $query = urlencode("$cuisine $diet restaurants in $location");

                    // API key for Google Places API (ensure to keep this secure)
                    $api_key = "AIzaSyCCZydLThH1j7AcsncDfttbcf4B0pyxL1I"; // Replace with your API key

                    // Construct the URL for the Google Places API
                    $url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=$query&key=$api_key";

                    // Fetch the data from the API
                    $response = file_get_contents($url);

                    // Decode the JSON response
                    $data = json_decode($response, true);

                    // Log the response for debugging purposes
                    error_log("API Response: " . print_r($data, true));

                    // Extract the results
                    $results = $data['results'];

                    // Output the results
                    if (!empty($results)) {
                        echo "<h1>Restaurant Recommendations</h1>";
                        foreach ($results as $result) {
                            echo "<div class='message'>";
                            echo "<div class='avatar'></div>";
                            echo "<div class='text'>";
                            echo "<strong>Name:</strong> " . htmlspecialchars($result['name']) . "<br>";
                            echo "<strong>Address:</strong> " . htmlspecialchars($result['formatted_address']) . "<br>";
                            echo "<strong>Rating:</strong> " . htmlspecialchars($result['rating']) . "<br>";

                            // Check if there are photos
                            if (!empty($result['photos'])) {
                                $photo_reference = $result['photos'][0]['photo_reference'];
                                $photo_url = "https://maps.googleapis.com/maps/api/place/photo?maxwidth=400&photoreference=$photo_reference&key=$api_key";
                                echo "<img src='$photo_url' alt='Restaurant Image' style='width:100%;border-radius:10px;margin-top:10px;'>";
                            }
                            
                            echo "</div></div>";
                        }
                    } else {
                        echo "<div class='message'>";
                        echo "<div class='avatar'></div>";
                        echo "<div class='text'>No recommendations found for the given inputs.</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='message'>";
                    echo "<div class='avatar'></div>";
                    echo "<div class='text'>Invalid request method.</div>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
