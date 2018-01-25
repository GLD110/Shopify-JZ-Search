<!DOCTYPE html>
<html>
<head>
  <title>Shopify APP</title>
  <link rel="stylesheet" media="all" href="/assets/application-e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855.css" data-turbolinks-track="true" />
  <script src="/assets/application-98a186a7931a9ae40020158cf10795154a6d271985e902ad1481acb93780d545.js" data-turbolinks-track="true"></script>
  <meta name="csrf-param" content="authenticity_token" />
<meta name="csrf-token" content="wzMG2VwL54YpC30wBfmmCbUeNidpOz2v7k1PI2XJEDMh6A1ZOWAvkf+e/lbeHyMBPZfGcq0Qon0V9fKlCXyYEA==" />
</head>
<body>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Shopify App ? Installation</title>

  <style>
    html, body { padding: 0; margin: 0; }

    body {
      font-family: "ProximaNovaLight", "Helvetica Neue", Helvetica, Arial, sans-serif;
      background-color: #f2f7fa;
    }

    h1 {
      font-weight: 300;
      font-size: 40px;
      margin-bottom: 10px;
    }

    .subhead {
      font-size: 17px;
      line-height: 32px;
      font-weight: 300;
      color: #969A9C;
    }

    input {
      width: 300px;
      height: 50px;
      padding: 10px;
      border: 1px solid #479CCf;
      color: #575757;
      background-color: #ffffff;
      box-sizing: border-box;
      border-radius: 4px 0 0 4px;
      font-size: 18px;
      float: left;
    }

    button {
      color: #ffffff;
      background-color: #3793cb;
      width: 100px;
      height: 50px;
      padding: 10px 20px 10px 20px;
      box-sizing: border-box;
      border: none;
      text-shadow: 0 1px 0 #3188bc;
      font-size: 18px;
      cursor: pointer;
      border-radius: 0 4px 4px 0;
      float: right;
    }

    button:hover {
      background-color: #479CCf;
    }

    form {
      display: block;
    }

    .container {
      text-align: center;
      margin-top: 100px;
      padding: 20px;
    }

    .container__form {
      width: 400px;
      margin: auto;
    }
  </style>
</head>
<body>

  <div class="container" role="main">
    <header>
      <h1>Shopify App ? Installation</h1>
      <p class="subhead">
        <label for="shop">Please enter the "myshopify" domain of your store</label>
      </p>
    </header>

    <div class="container__form">
      <form method="GET" action="<?PHP echo base_url( 'newstore/register' ); ?>">
        <input type="text" name="shop" id="shop" placeholder="blabla.myshopify.com" value = '<?PHP if( isset( $shop ) ) echo $shop; ?>'/>
        <button type="submit">Install</button>
      </form>
    </div>
  </div>

</body>
</html>
</body>
</html>
