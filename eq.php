<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equations</title>
    <link rel="stylesheet" href="styles.css">
    <style>

body {
    font-family: Arial, sans-serif;
    padding: 20px;
}

.equation {
    margin-bottom: 20px;
    border: 1px solid #ccc;
    padding: 10px;
}

.equation h2 {
    margin-top: 0;
}

.equation p {
    margin-bottom: 5px;
}

    </style>
</head>
<body>
    <div class="equation">
        <h2>Trigonometry Equation:</h2>
        <?php
        // Trigonometry equation
        $angle = 45; // in degrees
        $result = sin(deg2rad($angle)) + cos(deg2rad($angle));
        echo "sin($angle) + cos($angle) = $result";
        ?>
    </div>
    
    <div class="equation">
        <h2>Polynomial Equation:</h2>
        <?php
        // Polynomial equation
        $x = 2;
        $result = $x**2 + 2*$x + 1;
        echo "$x^2 + 2x + 1 = $result";
        ?>
    </div>

    <!-- Add more equations for chemistry and electronics here -->

</body>
</html>
