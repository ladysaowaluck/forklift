<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Live Monitor</title>
    
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --color-primary: #ffffff;
            --color-secondary: #004B8D;   
            --color-info: #FA7800;          
            --color-success: #28a745;      
            --color-warning: #B90019;      
            --color-inactive: #6c757d;      
            --color-background: #0d1117;  
            --color-card: #161b22;         
            --color-border: #30363d;
            --color-text-primary: #c9d1d9;
            --color-text-secondary: #8b949e;
        }
        
        body {
            background-color: var(--color-background);
            color: var(--color-text-primary);
            font-family: 'Roboto Mono', monospace;
            font-size: clamp(0.875rem, 1.5vw, 1rem);
            overflow-x: hidden;
        }

        .monitor-header {
            color: #fff;
            text-shadow: 0 0 5px var(--color-primary);
        }

        .monitor-table-container {
            width: 100%;
            padding: 0.5rem;
            overflow-x: auto;
        }

        @media (min-width: 768px) {
            .monitor-table-container {
                padding: 1rem;
            }
        }

        .monitor-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0.5rem;
            white-space: nowrap;
        }

        .monitor-table th {
            color: var(--color-text-secondary);
            text-transform: uppercase;
            padding: 0.5rem 0.75rem;
            text-align: left;
            font-size: 0.75rem;
            border-bottom: 2px solid var(--color-border);
        }

        @media (min-width: 768px) {
            .monitor-table th {
                padding: 0.75rem 1.5rem;
                font-size: 0.9rem;
            }
        }

        .monitor-table td {
            background-color: var(--color-card);
            padding: 0.75rem 0.75rem;
            vertical-align: middle;
            transition: background-color 0.3s ease;
            font-size: 0.85rem;
        }

        @media (min-width: 768px) {
            .monitor-table td {
                padding: 1.25rem 1.5rem;
                font-size: 1rem;
            }
        }
        
        .monitor-table tr td:first-child { border-top-left-radius: 0.5rem; border-bottom-left-radius: 0.5rem; }
        .monitor-table tr td:last-child { border-top-right-radius: 0.5rem; border-bottom-right-radius: 0.5rem; }

        .status-cell { text-align: center; }
        .status-badge {
            padding: 0.35em 0.8em;
            font-weight: 700;
            border-radius: 50rem;
            color: #fff;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
            font-size: 0.75rem;
            display: inline-block;
        }

        @media (min-width: 768px) {
            .status-badge {
                padding: 0.5em 1.2em;
                font-size: 0.9rem;
            }
        }

        .status-Pending { background-color: var(--color-inactive); }
        .status-Assigned { background-color: var(--color-primary); color: #000; }
        .status-In-Progress, .status-In\.Progress { background-color: var(--color-secondary); }
        .status-Arrived { background-color: var(--color-info); }
        .status-Completed { background-color: var(--color-success); }

        .route-col .from { color: #ff8b8b; }
        .route-col .to { color: #8bff8b; }
    </style>
</head>
<body>
    <div class="container-fluid py-3 px-2 px-md-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 mb-md-4 gap-2">
            <h1 class="fs-2 fs-md-1 fw-bolder monitor-header mb-0">
                <i class="fas fa-satellite-dish"></i> Live Monitor
            </h1>
            <div class="text-start text-md-end">
                <span id="clock" class="fs-5 fs-md-4 fw-bold"></span>
                <p id="last-updated" class="fs-6 mb-0" style="color: var(--color-text-secondary);"></p>
            </div>
        </div>
        
        <div class="monitor-table-container">
            <table class="monitor-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Route</th>
                        <th>Driver</th>
                        <th>Forklift</th>
                        <th class="text-center">Status</th>
                        <th class="text-end">Last Update</th>
                    </tr>
                </thead>
                <tbody id="monitor-body">
                    {{-- Rows will be injected here by JavaScript --}}
                </tbody>
            </table>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const tableBody = document.getElementById('monitor-body');
        const lastUpdatedEl = document.getElementById('last-updated');
        const clockEl = document.getElementById('clock');

        function renderTable(transactions) {
            transactions.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

            if (transactions.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center py-5 text-muted">No recent tasks found.</td></tr>';
                return;
            }

            const tableHTML = transactions.map(task => {
                const statusClass = `status-${task.status.replace(/\s+/g, '-')}`;
                const from = task.warehouse_from ? task.warehouse_from.name : 'N/A';
                const to = task.warehouse_to ? task.warehouse_to.name : 'N/A';
                const driver = task.driver ? task.driver.name : 'Unassigned';
                const forklift = task.forklift ? task.forklift.model : 'N/A';

                return `
                    <tr id="task-${task.transaction_id}">
                        <td>#${task.transaction_id}</td>
                        <td class="route-col"><span class="from">${from}</span> → <span class="to">${to}</span></td>
                        <td>${driver}</td>
                        <td>${forklift}</td>
                        <td class="status-cell"><span class="status-badge ${statusClass}">${task.status}</span></td>
                        <td class="text-end">${task.updated_human}</td>
                    </tr>
                `;
            }).join('');
            
            tableBody.innerHTML = tableHTML;
        }

        async function fetchTransactions() {
            try {
                const response = await fetch('{{ route("api.active_transactions") }}');
                if (!response.ok) throw new Error('Network response was not ok');
                const transactions = await response.json();
                renderTable(transactions);
                lastUpdatedEl.textContent = `Updated: ${new Date().toLocaleTimeString()}`;
            } catch (error) {
                console.error('Failed to fetch transactions:', error);
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center py-5 text-danger">Error loading data.</td></tr>';
            }
        }

        function updateClock() {
            if(clockEl) {
                clockEl.textContent = new Date().toLocaleTimeString('en-GB');
            }
        }

        fetchTransactions();
        updateClock();
        setInterval(fetchTransactions, 5000); 
        setInterval(updateClock, 1000);
    });
    </script>
</body>
</html>