<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Absensi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --primary: #6C63FF;
            --secondary: #48C9B0;
            --background: #f5f8fa;
            --white: #fff;
            --shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.11);
        }
        body {
            min-height: 100vh;
            background: linear-gradient(120deg, var(--primary) 50%, var(--secondary) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
        }
        .container {
            background: var(--white);
            padding: 2.5rem 2.5rem 2rem 2.5rem;
            border-radius: 1.3rem;
            box-shadow: var(--shadow);
            width: 350px;
            max-width: 95vw;
            text-align: center;
        }
        h1 {
            color: var(--primary);
            margin-bottom: 1.2rem;
            font-weight: 600;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: 500;
            color: #444;
            margin: 0.6rem 0 0.15rem 0;
            text-align: left;
        }
        input[type="email"], input[type="password"] {
            padding: 0.7rem;
            border: 1.5px solid #dcdcdc;
            border-radius: 0.6rem;
            margin-bottom: 0.9rem;
            background: #f7f7ff;
            font-size: 1.05rem;
            outline: none;
            transition: border 0.2s;
        }
        input[type="email"]:focus, input[type="password"]:focus {
            border: 1.5px solid var(--primary);
        }
        button[type="submit"] {
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border: none;
            color: var(--white);
            padding: 0.9rem;
            font-size: 1.1rem;
            border-radius: 0.6rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 0.6rem;
            transition: background 0.2s, transform 0.14s;
        }
        button[type="submit"]:hover {
            background: linear-gradient(90deg, var(--secondary), var(--primary));
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 2px 12px 0 rgba(76,99,255,0.13);
        }
        .result {
            margin-top: 1.2rem;
            min-height: 2.1em;
            color: #fff;
            background: linear-gradient(90deg, #ff5858 35%, #ffae42 100%);
            padding: 0.6em 1em;
            border-radius: 0.5em;
            box-shadow: 0 2px 6px 0 rgba(255,88,88,0.09);
            font-size: 1em;
            display: none;
        }
        .result.success {
            background: linear-gradient(90deg, #48C9B0 40%, #6C63FF 100%);
        }
        .avatar {
            width: 72px;
            height: 72px;
            margin-bottom: 1.2rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #6C63FF 60%, #48C9B0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .avatar svg {
            width: 40px;
            height: 40px;
            fill: #fff;
        }
        @media (max-width: 480px) {
            .container {
                padding: 1.2rem;
                border-radius: 0.8rem;
            }
            .avatar {
                width: 54px;
                height: 54px;
            }
            .avatar svg {
                width: 28px; height: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="avatar">
            <!-- User icon SVG -->
            <svg viewBox="0 0 24 24"><path d="M12,12A5,5 0 1,0 12,2A5,5 0 1,0 12,12M12,14.2C15.5,14.2 19,15.7 19,17.25V19H5V17.25C5,15.7 8.5,14.2 12,14.2Z"/></svg>
        </div>
        <h1>Login Absensi</h1>
        <form id="loginForm" autocomplete="off">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="you@email.com" required>
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="••••••" required>
            
            <button type="submit">Login</button>
        </form>
        <div class="result" id="result"></div>
    </div>
    <script>
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        let form = e.target;
        let data = {
            email: form.email.value,
            password: form.password.value
        };
        let resultDiv = document.getElementById('result');
        resultDiv.style.display = 'block';
        resultDiv.textContent = 'Loading...';
        resultDiv.className = 'result';

        try {
            let response = await fetch('/api/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            let res = await response.json();
            if (response.ok && res.token && res.user) {
                // Simpan token ke localStorage
                localStorage.setItem('api_token', res.token);
                localStorage.setItem('user_role', res.user.role);
                localStorage.setItem('user_name', res.user.name);

                resultDiv.textContent = 'Login sukses! Mengalihkan...';
                resultDiv.className = 'result success';

                setTimeout(() => {
                    if(res.user.role === 'admin') {
                        window.location.href = '/admin/dashboard';
                    } else {
                        window.location.href = '/absensi';
                    }
                }, 1100);
            } else {
                resultDiv.textContent = res.message || 'Login gagal';
                resultDiv.className = 'result';
            }
        } catch (err) {
            resultDiv.textContent = 'Terjadi error: ' + err;
            resultDiv.className = 'result';
        }
    });
    </script>
</body>
</html>