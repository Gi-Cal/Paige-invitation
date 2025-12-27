<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - RSVP Dashboard</title>
   
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #f5e6d3 0%, #d4a574 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #4d683e 0%, #3d5331 100%);
            padding: 40px;
            text-align: center;
            color: #fff1aa;
            position: relative;
        }

        .header::before,
        .header::after {
            content: '✨';
            position: absolute;
            font-size: 32px;
            animation: sparkle 2s infinite;
        }

        .header::before {
            top: 20px;
            left: 40px;
        }

        .header::after {
            top: 20px;
            right: 40px;
            animation-delay: 1s;
        }

        @keyframes sparkle {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        h1 {
            font-family: 'Cinzel Decorative', serif;
            font-size: 3rem;
            margin-bottom: 10px;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.3);
            letter-spacing: 3px;
        }

        .subtitle {
            font-size: 1.3rem;
            font-style: italic;
            opacity: 0.9;
        }

        .actions {
            padding: 30px 40px;
            background: #fef9e7;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #D6C17A;
        }

        .total-count {
            font-size: 1.5rem;
            color: #4d683e;
            font-weight: 700;
        }

        .total-count span {
            font-size: 2rem;
            color: #D6C17A;
            font-weight: 700;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 30px;
            background: linear-gradient(135deg, #D6C17A 0%, #b8a05f 100%);
            color: #4d683e;
            text-decoration: none;
            border-radius: 10px;
            font-size: 1.2rem;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(214, 193, 122, 0.4);
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(214, 193, 122, 0.6);
        }

        .btn-logout {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }

        .btn-logout:hover {
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.6);
        }

        .table-container {
            padding: 40px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        thead {
            background: linear-gradient(135deg, #4d683e 0%, #3d5331 100%);
            color: #fff1aa;
        }

        th {
            padding: 18px 15px;
            text-align: left;
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            border-bottom: 3px solid #D6C17A;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
            color: #333;
            font-size: 1.05rem;
        }

        tbody tr {
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background: #fef9e7;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .status-badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.95rem;
            text-transform: uppercase;
        }

        .status-yes {
            background: #d4edda;
            color: #155724;
            border: 2px solid #28a745;
        }

        .status-no {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #dc3545;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .empty-state h2 {
            font-family: 'Cinzel Decorative', serif;
            font-size: 2rem;
            color: #4d683e;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 1.2rem;
            color: #666;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }

            .actions {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .table-container {
                padding: 20px;
            }

            table {
                font-size: 0.9rem;
            }

            th, td {
                padding: 10px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 RSVP Dashboard</h1>
            <p class="subtitle">Paige's First Birthday Celebration</p>
        </div>

        <div class="actions">
            <div class="total-count">
                Total RSVPs: <span>{{ count($data) > 0 ? count($data) - 1 : 0 }}</span>
            </div>
            <div style="display: flex; gap: 15px;">
                <a href="{{ route('admin.download') }}" class="btn">
                    📥 Download Excel
                </a>
                <a href="{{ route('admin.logout') }}" class="btn btn-logout">
                    🚪 Logout
                </a>
            </div>
        </div>

        <div class="table-container">
            @if(count($data) > 1)
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Attending</th>
                            <th>Additional Guests</th>
                            <th>Message</th>
                            <th>Submitted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i = 1; $i < count($data); $i++)
                            <tr>
                                <td><strong>{{ $data[$i][0] ?? 'N/A' }}</strong></td>
                                <td>{{ $data[$i][1] ?? 'N/A' }}</td>
                                <td>
                                    <span class="status-badge status-{{ strtolower($data[$i][2] ?? 'no') }}">
                                        {{ $data[$i][2] ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>{{ $data[$i][3] ?: '-' }}</td>
                                <td>{{ $data[$i][4] ?: '-' }}</td>
                                <td>{{ $data[$i][5] ?? 'N/A' }}</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">📭</div>
                    <h2>No RSVPs Yet</h2>
                    <p>When guests start responding, their information will appear here.</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>