<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    @vite('resources/css/welcome_style.css')
    
</head>
<body>
    <div class="mother-container">
        <div class="log-window">
            <img src="/images/smartpay_logo.png" id="smartpay-logo">
            <h1>SMARTPAY</h1>
            <p>Learner's Portal</p>
            <form action="{{ route('student.login') }}" method="POST">
                @csrf
                <label for="logcredential">Username or LRN:</label>
                <input type="text" id="logcredential" name="logcredential" required><br><br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br><br>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>

    <!-- Only the functional login form below is kept -->
        @if(session('error'))
            <div id="popup-overlay" style="position:fixed;inset:0;z-index:999;background:rgba(0,0,0,0.01);" onclick="closePopup(event)">
                <div class="pop-up-window" id="popup-window" onclick="event.stopPropagation()">
                    <span style="color:red; font-size: 1.2em; margin-bottom: 20px; text-align: center;">{{ session('error') }}</span>
                    <button onclick="closePopup(event)" style="margin-top: 30px; padding: 8px 24px; background: #007bff; color: #fff; border: none; border-radius: 4px; font-size: 1em; cursor: pointer;">Try Again</button>
                </div>
            </div>
            <script>
                function closePopup(e) {
                    document.getElementById('popup-overlay').style.display = 'none';
                }
            </script>
        @endif
        
</body>
</html>