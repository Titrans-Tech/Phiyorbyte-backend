<!-- resources/views/api-endpoints.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Endpoints</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            padding-top: 12px;
            padding-bottom: 12px;
            background-color: #f2f2f2;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>API Endpoints</h1>

    <table>
        <thead>
            <tr>
                <th>Method</th>
                <th>URI</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($routes as $route)
                <tr>
                    <td>{{ $route['method'] }}</td>
                    <td>{{ $route['uri'] }}</td>
                    <td>{{ $route['name'] ?? 'N/A' }}</td>
                    <td>{{ $route['action'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
