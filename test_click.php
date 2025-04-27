<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>クリックカウントテスト</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-button { padding: 10px; margin: 10px; }
        #result { margin-top: 20px; }
    </style>
</head>
<body>
    <h2>クリックカウントテスト</h2>
    
    <button class="test-button" onclick="testClick('getStartedBtn')">
        Test Get Started Button
    </button>
    
    <button class="test-button" onclick="testClick('learnMoreBtn')">
        Test Learn More Button
    </button>
    
    <div id="result"></div>

    <script>
    async function testClick(buttonId) {
        try {
            const response = await fetch('update_count.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ button_id: buttonId })
            });

            const data = await response.json();
            document.getElementById('result').innerHTML += 
                `<p>${buttonId}: ${JSON.stringify(data)}</p>`;
            
        } catch (error) {
            document.getElementById('result').innerHTML += 
                `<p style="color: red">Error: ${error.message}</p>`;
        }
    }
    </script>
</body>
</html> 