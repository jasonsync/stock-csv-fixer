
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- <script src="TableFilter/tablefilter.js"></script> -->
  <title>Woocommerce CSV file fixer</title>
</head>

<body>
  <h1>Woocommerce CSV file fixer</h1>
  <style>
    body {
      margin: 0;
      width: auto;
      font-family: sans-serif;
      background-color: #464646;
      color: #d6d6d6;
    }

    h1,
    h3 {
      text-align: center;
      padding: 20px;
    }

    .flex-container {
      display: flex;
      justify-content: center;
    }

  </style>
  <div class="flex-container">
    <form accept-charset="utf-8" enctype='multipart/form-data' action="upload.php" method="post" name="form">
      <h4>Step 1 - Choose file. Only select a woocommerce-order-export.csv file
        <br />Step 2 - Click "Process CSV File"<br />Step 3 - Your file will be downloaded once processed."<br />
      </h4>
      <input type="file" name="fileupload" value="" />
      <br /><br />
      <input type="submit" name="btnupload" value="Process CSV File" />
    </form>
  </div>
</body>

</html>
