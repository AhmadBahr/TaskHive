<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Hive Backend</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: 300;
        }
        .status {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 10px 20px;
            border-radius: 25px;
            margin-top: 15px;
            font-size: 1.1em;
        }
        .content {
            padding: 40px;
        }
        .section {
            margin-bottom: 40px;
        }
        .section h2 {
            color: #4facfe;
            border-bottom: 2px solid #4facfe;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .feature {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #4facfe;
        }
        .endpoints {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
        }
        .endpoint {
            margin-bottom: 10px;
            padding: 8px;
            background: white;
            border-radius: 4px;
            border-left: 3px solid #28a745;
        }
        .method {
            font-weight: bold;
            color: #28a745;
        }
        .demo-user {
            background: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #2196f3;
        }
        .demo-user h3 {
            margin-top: 0;
            color: #1976d2;
        }
        .btn {
            display: inline-block;
            background: #4facfe;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 10px 10px 10px 0;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #3d8bfe;
        }
        .success {
            color: #28a745;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Task Hive Backend</h1>
            <div class="status">{{ $message }}</div>
            <div class="status">Database: <span class="success">{{ $database }}</span></div>
        </div>
        
        <div class="content">
            <div class="section">
                <h2>✨ Features Implemented</h2>
                <div class="features">
                    @foreach($features as $feature)
                        <div class="feature">{{ $feature }}</div>
                    @endforeach
                </div>
            </div>

            <div class="section">
                <h2>🔗 API Endpoints</h2>
                <div class="endpoints">
                    @foreach($endpoints as $endpoint => $description)
                        <div class="endpoint">
                            <span class="method">{{ $endpoint }}</span> - {{ $description }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="section">
                <h2>👤 Demo User</h2>
                <div class="demo-user">
                    <h3>Test Account</h3>
                    <p><strong>Email:</strong> demo@taskhive.com</p>
                    <p><strong>Password:</strong> password</p>
                    <a href="/login" class="btn">Login to Dashboard</a>
                    <a href="/register" class="btn">Create New Account</a>
                </div>
            </div>

            <div class="section">
                <h2>🎯 Next Steps</h2>
                <p>The Task Hive backend is now fully operational with:</p>
                <ul>
                    <li>✅ PostgreSQL database connected and migrated</li>
                    <li>✅ Demo data seeded (user, board, columns, tasks)</li>
                    <li>✅ Authentication system ready</li>
                    <li>✅ RESTful API endpoints available</li>
                    <li>✅ Authorization policies implemented</li>
                </ul>
                <p>You can now:</p>
                <ul>
                    <li>Login with the demo account to explore the dashboard</li>
                    <li>Create new boards and manage tasks</li>
                    <li>Use the API endpoints for frontend integration</li>
                    <li>Extend the functionality as needed</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
