<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <style>
        body {
            background-color: #33304eff;
            font-family: Arial;
        }
        .login-box {
            width: 350px;
            margin: 120px auto;
            background: white;
            padding: 20px;
            box-shadow: 0px 0px 10px gray;
            border-radius: 5px;
        }
        h2 {
            text-align: center;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 8px;
        }
        button {
            width: 100%;
            padding: 8px;
            background-color: #0066cc;
            color: white;
            border: none;
            margin-top: 15px;
        }
        .note {
            font-size: 12px;
            color: gray;
            text-align: center;
            margin-top: 10px;
        }
    </style>

    <script>
        function validateLogin() {
            var id = document.getElementById("userid").value;
            var pass = document.getElementById("password").value;

            if (id == "" || pass == "") {
                alert("All fields are required");
                return false;
            }

            if (id.length < 5) {
                alert("Invalid User ID");
                return false;
            }

            return true;
        }
    </script>
</head>

<body>

<div class="login-box">
    <h2>Port Management Login</h2>

    <form action="telogincheck.php" method="post" onsubmit="return validateLogin()">

        User ID:<br>
        <input type="text" name="userid" id="userid" placeholder="ADM001 / CUS001 / OWN001">

        Password:<br>
        <input type="password" name="password" id="password">

        <button type="submit">Login</button>
    </form>

    <div class="note">
        Admin: ADMxxx | Customer: CUSxxx | Owner: OWNxxx
    </div>
</div>

</body>
</html>
