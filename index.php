<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "mahasiswa_db";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get student data grouped by jurusan (department)
$chartQuery = "SELECT jurusan, COUNT(*) as jml_mahasiswa FROM mahasiswa GROUP BY jurusan";
$chartResult = $conn->query($chartQuery);

$jurusan = [];
$jumlah = [];

// Fetch data for chart
if ($chartResult->num_rows > 0) {
    while($row = $chartResult->fetch_assoc()) {
        $jurusan[] = $row["jurusan"];
        $jumlah[] = $row["jml_mahasiswa"];
    }
}

// Query to get all student data for table
$tableQuery = "SELECT * FROM mahasiswa ORDER BY jurusan, nama";
$tableResult = $conn->query($tableQuery);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #3f37c9;
            --accent: #4895ef;
            --success: #4cc9f0;
            --warning: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --border: #dee2e6;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            color: var(--dark);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            text-align: center;
        }
        
        h1 {
            color: var(--primary);
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .subtitle {
            color: var(--gray);
            font-size: 1.1rem;
            margin-bottom: 10px;
        }
        
        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
        }
        
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--secondary);
        }
        
        .chart-container {
            position: relative;
            height: 400px;
            margin-bottom: 20px;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background-color: var(--primary);
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .btn:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn i {
            font-size: 1rem;
        }
        
        .actions {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        
        th {
            background-color: var(--primary);
            color: white;
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        tr:nth-child(even) {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .badge-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .badge-success {
            background-color: var(--success);
            color: white;
        }
        
        .badge-warning {
            background-color: var(--warning);
            color: white;
        }
        
        .badge-accent {
            background-color: var(--accent);
            color: white;
        }
        
        .badge-secondary {
            background-color: var(--secondary);
            color: white;
        }
        
        .text-center {
            text-align: center;
        }
        
        .responsive-table {
            overflow-x: auto;
        }
        
        footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            color: var(--gray);
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
            
            .card {
                padding: 15px;
            }
            
            th, td {
                padding: 8px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Data Mahasiswa</h1>
            <p class="subtitle">Visualisasi dan Tabel Data Mahasiswa</p>
        </header>
        
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-chart-bar"></i> Grafik Jumlah Mahasiswa per Jurusan</h2>
            </div>
            
            <div class="chart-container">
                <canvas id="myChart"></canvas>
            </div>
            
            <div class="actions">
                <button id="downloadPDF" class="btn">
                    <i class="fas fa-file-pdf"></i> Download PDF
                </button>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-table"></i> Daftar Mahasiswa</h2>
            </div>
            
            <div class="responsive-table">
                <table id="mahasiswaTable">
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Jurusan</th>
                            <th>Jenis Kelamin</th>
                            <th>Alamat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($tableResult->num_rows > 0) {
                            $badgeClasses = [
                                'Teknik Informatika' => 'badge-primary',
                                'Sistem Informasi' => 'badge-success',
                                'Manajemen Informatika' => 'badge-warning',
                                'Teknik Komputer' => 'badge-accent',
                                'Teknik Elektro' => 'badge-secondary'
                            ];
                            
                            while($row = $tableResult->fetch_assoc()) {
                                $badgeClass = isset($badgeClasses[$row["jurusan"]]) ? $badgeClasses[$row["jurusan"]] : 'badge-primary';
                                
                                echo "<tr>";
                                echo "<td>" . $row["nim"] . "</td>";
                                echo "<td>" . $row["nama"] . "</td>";
                                echo "<td><span class='badge " . $badgeClass . "'>" . $row["jurusan"] . "</span></td>";
                                echo "<td>" . ($row["jenis_kelamin"] == 'L' ? 'Laki-laki' : 'Perempuan') . "</td>";
                                echo "<td>" . $row["alamat"] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>Tidak ada data mahasiswa</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <footer>
        &copy; <?php echo date("Y"); ?> Data Mahasiswa - Dibuat dengan PHP, Chart.js, dan jsPDF
    </footer>

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Include jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    
    <!-- Include jsPDF AutoTable plugin for table export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    
    <!-- Include html2canvas for capturing chart as image -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    
    <script>
        // PHP data converted to JavaScript
        const jurusan = <?php echo json_encode($jurusan); ?>;
        const jumlah = <?php echo json_encode($jumlah); ?>;
        
        // Define colors for departments (matching badge colors)
        const departmentColors = {
            'Teknik Informatika': {
                background: 'rgba(67, 97, 238, 0.7)',
                border: 'rgb(67, 97, 238)'
            },
            'Sistem Informasi': {
                background: 'rgba(76, 201, 240, 0.7)',
                border: 'rgb(76, 201, 240)'
            },
            'Manajemen Informatika': {
                background: 'rgba(247, 37, 133, 0.7)',
                border: 'rgb(247, 37, 133)'
            },
            'Teknik Komputer': {
                background: 'rgba(72, 149, 239, 0.7)',
                border: 'rgb(72, 149, 239)'
            },
            'Teknik Elektro': {
                background: 'rgba(63, 55, 201, 0.7)',
                border: 'rgb(63, 55, 201)'
            }
        };
        
        // Map colors to departments
        const backgroundColors = jurusan.map(dept => 
            departmentColors[dept] ? departmentColors[dept].background : 'rgba(67, 97, 238, 0.7)'
        );
        
        const borderColors = jurusan.map(dept => 
            departmentColors[dept] ? departmentColors[dept].border : 'rgb(67, 97, 238)'
        );
        
        // Get the canvas element
        const ctx = document.getElementById('myChart').getContext('2d');
        
        // Create the chart
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: jurusan,
                datasets: [{
                    label: 'Jumlah Mahasiswa',
                    data: jumlah,
                    backgroundColor: backgroundColors,
                    borderColor: borderColors,
                    borderWidth: 1,
                    borderRadius: 5,
                    maxBarThickness: 60
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                family: "'Segoe UI', sans-serif"
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                family: "'Segoe UI', sans-serif"
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Distribusi Mahasiswa per Jurusan',
                        font: {
                            size: 18,
                            family: "'Segoe UI', sans-serif",
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 20
                        }
                    },
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                family: "'Segoe UI', sans-serif"
                            },
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleFont: {
                            family: "'Segoe UI', sans-serif",
                            size: 14
                        },
                        bodyFont: {
                            family: "'Segoe UI', sans-serif",
                            size: 13
                        },
                        padding: 12,
                        cornerRadius: 6,
                        displayColors: false
                    }
                }
            }
        });
        
        // PDF Download functionality
        document.getElementById('downloadPDF').addEventListener('click', function() {
            // Create PDF using jsPDF
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('landscape', 'mm', 'a4');
            
            // Add title to PDF
            doc.setFont("helvetica", "bold");
            doc.setFontSize(20);
            doc.setTextColor(67, 97, 238);
            doc.text('Data Mahasiswa', 20, 20);
            
            doc.setFont("helvetica", "normal");
            doc.setFontSize(12);
            doc.setTextColor(108, 117, 125);
            doc.text('Laporan Data dan Grafik Mahasiswa', 20, 28);
            
            // Capture the chart as an image
            html2canvas(document.getElementById('myChart')).then(function(canvas) {
                // Convert canvas to image data
                const imgData = canvas.toDataURL('image/png');
                
                // Calculate dimensions to fit on PDF
                const imgWidth = 250;
                const imgHeight = canvas.height * imgWidth / canvas.width;
                
                // Add image to PDF
                doc.addImage(imgData, 'PNG', 20, 35, imgWidth, imgHeight);
                
                // Add summary table
                doc.setFont("helvetica", "bold");
                doc.setFontSize(14);
                doc.setTextColor(0, 0, 0);
                doc.text('Ringkasan Jumlah Mahasiswa per Jurusan', 20, imgHeight + 45);
                
                // Create summary table
                const summaryTableData = [];
                for (let i = 0; i < jurusan.length; i++) {
                    summaryTableData.push([jurusan[i], jumlah[i].toString()]);
                }
                
                doc.autoTable({
                    startY: imgHeight + 50,
                    head: [['Jurusan', 'Jumlah Mahasiswa']],
                    body: summaryTableData,
                    theme: 'grid',
                    headStyles: {
                        fillColor: [67, 97, 238],
                        textColor: [255, 255, 255],
                        fontStyle: 'bold'
                    },
                    styles: {
                        font: 'helvetica',
                        fontSize: 10
                    },
                    alternateRowStyles: {
                        fillColor: [245, 245, 245]
                    }
                });
                
                // Add new page for detailed table
                doc.addPage();
                
                // Add title for detailed table
                doc.setFont("helvetica", "bold");
                doc.setFontSize(16);
                doc.setTextColor(67, 97, 238);
                doc.text('Daftar Lengkap Mahasiswa', 20, 20);
                
                // Get table data
                const table = document.getElementById('mahasiswaTable');
                
                // Extract table data
                const tableData = [];
                for (let i = 1; i < table.rows.length; i++) {
                    const row = table.rows[i];
                    const rowData = [];
                    
                    // Skip if it's a "no data" row
                    if (row.cells.length === 1 && row.cells[0].colSpan > 1) {
                        continue;
                    }
                    
                    for (let j = 0; j < row.cells.length; j++) {
                        // For the jurusan column, extract text content without the badge styling
                        if (j === 2) {
                            rowData.push(row.cells[j].textContent.trim());
                        } else {
                            rowData.push(row.cells[j].textContent);
                        }
                    }
                    tableData.push(rowData);
                }
                
                // Add detailed table to PDF
                doc.autoTable({
                    startY: 30,
                    head: [['NIM', 'Nama', 'Jurusan', 'Jenis Kelamin', 'Alamat']],
                    body: tableData,
                    theme: 'grid',
                    headStyles: {
                        fillColor: [67, 97, 238],
                        textColor: [255, 255, 255],
                        fontStyle: 'bold'
                    },
                    styles: {
                        font: 'helvetica',
                        fontSize: 9,
                        cellPadding: 3
                    },
                    columnStyles: {
                        0: { cellWidth: 25 },
                        1: { cellWidth: 40 },
                        2: { cellWidth: 40 },
                        3: { cellWidth: 25 },
                        4: { cellWidth: 50 }
                    },
                    alternateRowStyles: {
                        fillColor: [245, 245, 245]
                    }
                });
                
                // Add footer with timestamp
                const pageCount = doc.internal.getNumberOfPages();
                for (let i = 1; i <= pageCount; i++) {
                    doc.setPage(i);
                    doc.setFontSize(8);
                    doc.setTextColor(128, 128, 128);
                    const today = new Date();
                    doc.text(`Dicetak pada: ${today.toLocaleString()}`, 20, doc.internal.pageSize.height - 10);
                    doc.text(`Halaman ${i} dari ${pageCount}`, doc.internal.pageSize.width - 40, doc.internal.pageSize.height - 10);
                }
                
                // Save the PDF
                doc.save('data_mahasiswa.pdf');
            });
        });
    </script>
</body>
</html>

