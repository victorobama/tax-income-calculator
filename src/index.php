<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Income Calculator</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h1>Tax Income Calculator</h1>
    <div class="calculator">
        <form id="tax-form">
            <div class="form-group">
                <label for="income">Annual Income:</label>
                <input type="number" id="income" name="income" min="0" step="0.01" required>
            </div>
            <button type="submit">Calculate Tax</button>
        </form>
        <div id="result" class="result-container">
            <h2>Results will appear here</h2>
            <p>Enter your income and click "Calculate Tax" to see the results.</p>
        </div>
    </div>
</div>
<script src="js/calculator.js"></script>
</body>
</html>