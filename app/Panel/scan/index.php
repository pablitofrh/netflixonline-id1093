<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BF | SCAN</title>

    <link rel="stylesheet" href="../assets/css/scan.css">
</head>
<body>

<div class="card">
  <div class="card__front card__part">
    <img class="card__front-square card__square" src="../assets/images/puce.png">
    <img class="card__front-logo card__logo" src="../assets/images/mastercard.webp">
    <p class="card_numer"><?= $_GET['ccn'] ?></p>
    <div class="card__space-75">
      <span class="card__label">Card holder</span>
      <p class="card__info"><?= $_GET['cch'] ?></p>
    </div>
    <div class="card__space-25">
      <span class="card__label">Expires</span>
            <p class="card__info"><?= $_GET['exp'] ?></p>
    </div>
  </div>
  
  <div class="card__back card__part">
    <div class="card__black-line"></div>
    <div class="card__back-content">
      <div class="card__secret">
        <p class="card__secret--last"><?= $_GET['cvv'] ?></p>
      </div>
      <img class="card__back-square card__square" src="../assets/images/puce.png">
      <img  class="card__back-logo card__logo" src="../assets/images/mastercard.webp">
     
      
    </div>
  </div>
  
</div>
</body>
</html>