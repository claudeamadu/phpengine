<?php
error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Error 403 - Forbidden</title>
  <!-- Favicon Icon -->
  <link rel="icon" type="image/png" href="assets/images/favicon.png">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background-color: #f2f2f2;
    }

    .container {
      text-align: center;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    h1 {
      font-size: 48px;
      color: #333;
    }

    p {
      font-size: 18px;
      color: #666;
      margin-bottom: 20px;
    }

    a {
      display: inline-block;
      background-color: #007bff;
      color: #fff;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }

    a:hover {
      background-color: #0056b3;
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Oops! Error 403</h1>
    <p>Sorry, the page you are looking for is forbidden.</p>
    <a href="<?= ROUTE_URL ?>">Go to Home Page</a>
  </div>
</body>

</html>