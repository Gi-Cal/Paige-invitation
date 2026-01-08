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
            flex-wrap: wrap;
            gap: 20px;
        }

        .stats {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            color: #D6C17A;
            font-weight: 700;
            display: block;
        }

        .stat-label {
            font-size: 1rem;
            color: #4d683e;
            font-weight: 600;
            text-transform: uppercase;
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

        .filter-group {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .filter-label {
            font-size: 1.1rem;
            color: #4d683e;
            font-weight: 600;
        }

        #attendingFilter {
            padding: 12px 20px;
            border: 2px solid #D6C17A;
            border-radius: 10px;
            font-size: 1.1rem;
            color: #4d683e;
            font-weight: 600;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #attendingFilter:hover {
            border-color: #b8a05f;
            box-shadow: 0 2px 10px rgba(214, 193, 122, 0.3);
        }

        #attendingFilter:focus {
            outline: none;
            border-color: #4d683e;
            box-shadow: 0 0 0 3px rgba(77, 104, 62, 0.1);
        }

        .content-wrapper {
            padding: 40px;
        }

        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            min-width: 800px;
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

        /* Pagination Styles - FIXED: Now outside scrollable area */
        .pagination-container {
            padding: 20px 0;
            background: white;
        }

        .pagination-wrapper {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
            justify-content: center;
        }

        .pagination-btn {
            padding: 10px 16px;
            border: 2px solid #D6C17A;
            background: white;
            color: #4d683e;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            min-width: fit-content;
        }

        .pagination-btn:hover:not(.disabled) {
            background: #fef9e7;
            border-color: #b8a05f;
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(214, 193, 122, 0.3);
        }

        .pagination-btn.active {
            background: linear-gradient(135deg, #D6C17A 0%, #b8a05f 100%);
            color: white;
            border-color: #D6C17A;
        }

        .pagination-btn.disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .pagination-ellipsis {
            padding: 0 8px;
            color: #4d683e;
            font-weight: 600;
        }

        .pagination-info {
            padding: 10px 16px;
            background: #fef9e7;
            border: 2px solid #D6C17A;
            border-radius: 8px;
            color: #4d683e;
            font-weight: 600;
            font-size: 0.95rem;
            white-space: nowrap;
            width: 100%;
            text-align: center;
            margin-top: 10px;
        }

        .pagination-arrow {
            font-size: 1.1rem;
            line-height: 1;
        }

        .pagination-text {
            display: inline;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .container {
                margin: 0 10px;
            }

            .actions {
                flex-direction: column;
                align-items: stretch;
            }

            .stats {
                width: 100%;
                justify-content: center;
            }

            .filter-group,
            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .header h1 {
                font-size: 2rem;
            }

            .subtitle {
                font-size: 1.1rem;
            }

            .header::before,
            .header::after {
                font-size: 24px;
            }

            .header {
                padding: 25px 20px;
            }

            .actions {
                padding: 20px;
            }

            .stats {
                flex-direction: row;
                gap: 20px;
                width: 100%;
                justify-content: space-around;
            }

            .stat-item {
                min-width: 100px;
            }

            .stat-number {
                font-size: 2rem;
            }

            .stat-label {
                font-size: 0.85rem;
            }

            .filter-group {
                flex-direction: column;
                width: 100%;
            }

            #attendingFilter {
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
                padding: 12px 20px;
                font-size: 1.1rem;
            }

            .content-wrapper {
                padding: 15px;
            }

            table {
                font-size: 0.85rem;
            }

            th, td {
                padding: 10px 8px;
                font-size: 0.85rem;
            }

            th {
                font-size: 0.9rem;
            }

            .status-badge {
                font-size: 0.8rem;
                padding: 5px 10px;
            }

            .pagination-btn {
                padding: 8px 12px;
                font-size: 0.9rem;
                min-width: 40px;
            }

            .pagination-text {
                display: none;
            }

            .pagination-arrow {
                font-size: 1rem;
            }

            .pagination-info {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 480px) {
            .header h1 {
                font-size: 1.5rem;
                letter-spacing: 1px;
            }

            .subtitle {
                font-size: 0.95rem;
            }

            .stats {
                flex-direction: column;
                gap: 15px;
            }

            .stat-number {
                font-size: 1.8rem;
            }

            .pagination-btn {
                padding: 8px 10px;
                font-size: 0.85rem;
                min-width: 36px;
            }

            .pagination-ellipsis {
                padding: 0 4px;
                font-size: 0.9rem;
            }

            .empty-state-icon {
                font-size: 3rem;
            }

            .empty-state h2 {
                font-size: 1.5rem;
            }

            .empty-state p {
                font-size: 1rem;
            }
        }

        /* Landscape Mobile */
        @media (max-width: 768px) and (orientation: landscape) {
            .header {
                padding: 20px;
            }

            .header h1 {
                font-size: 1.8rem;
            }

            .stats {
                flex-direction: row;
                gap: 15px;
            }

            .actions {
                padding: 15px;
            }
        }

        /* Button group styling for mobile */
        .button-group {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
            width: 100%;
        }

        @media (max-width: 768px) {
            .button-group {
                flex-direction: column;
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
            <div class="stats">
                <div class="stat-item">
                    <span class="stat-number total">{{ $rsvps->count() }}</span>
                    <span class="stat-label">Total RSVPs</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number attending">{{ $rsvps->where('attending', 'yes')->count() }}</span>
                    <span class="stat-label">Attending</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number not-attending">{{ $rsvps->where('attending', 'no')->count() }}</span>
                    <span class="stat-label">Not Attending</span>
                </div>
            </div>
            <div class="button-group">
                <div class="filter-group">
                    <label for="attendingFilter" class="filter-label">Filter:</label>
                    <select id="attendingFilter">
                        <option value="all">All Status</option>
                        <option value="yes">Attending</option>
                        <option value="no">Not Attending</option>
                    </select>
                </div>
                <a href="#" class="btn btn-download" data-download-url="{{ route('admin.download') }}">
                    📥 Download Excel
                </a>
                <a href="{{ route('admin.logout') }}" class="btn btn-logout">
                    🚪 Logout
                </a>
            </div>
        </div>

        <div class="content-wrapper">
            @if($rsvps->isNotEmpty())
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Attending</th>
                                <th>Additional Guests</th>
                                <th>Message</th>
                                <th>Submitted At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rsvps as $index => $rsvp)
                                <tr>
                                    <td><strong>{{ $index + 1 }}</strong></td>
                                    <td><strong>{{ $rsvp->name }}</strong></td>
                                    <td>{{ $rsvp->email }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $rsvp->attending }}">
                                            {{ ucfirst($rsvp->attending) }}
                                        </span>
                                    </td>
                                    <td>{{ $rsvp->additional_guests ?: '-' }}</td>
                                    <td>{{ $rsvp->message ?: '-' }}</td>
                                    <td>{{ $rsvp->created_at->format('M d, Y h:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Container - NOW OUTSIDE TABLE CONTAINER -->
                <div class="pagination-container">
                    <div id="pagination"></div>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">📭</div>
                    <h2>No RSVPs Yet</h2>
                    <p>When guests start responding, their information will appear here.</p>
                </div>
            @endif
        </div>
    </div>

@vite(['resources/js/admin-rsvp.js'])
</body>
</html>