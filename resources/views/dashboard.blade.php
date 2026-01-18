@extends('layouts.app')

@section('content')
    <div class="container" style="padding-top: 2rem;">
        <div class="card">
            <h2 class="mb-4">Selamat Datang di Dashboard</h2>
            <p>Anda login sebagai role: <strong>{{ Auth::user()->role }}</strong></p>

            <div
                style="margin-top: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <h3
                        style="font-size: 0.875rem; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.05em;">
                        Total Barang</h3>
                    <p style="font-size: 2rem; font-weight: 700; color: var(--color-primary);">
                        {{ \App\Models\Item::count() }}
                    </p>
                </div>

                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <h3
                        style="font-size: 0.875rem; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.05em;">
                        Peminjaman Aktif</h3>
                    <p style="font-size: 2rem; font-weight: 700; color: #f59e0b;">
                        {{ \App\Models\Borrowing::where('status', 'approved')->count() }}
                    </p>
                </div>
            </div>
        </div>

        @if(Auth::user()->isAdmin())
            <div style="margin-top: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem;">
                <!-- Borrowing Trend Chart -->
                <div style="background: #fff; padding: 1.5rem; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <h3 style="font-size: 1.1rem; font-weight: 600; color: #1f2937; margin-bottom: 1rem;">Tren Peminjaman (30 Hari)</h3>
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="borrowingTrendChart"></canvas>
                    </div>
                </div>

                <!-- Borrowing Status Chart -->
                <div style="background: #fff; padding: 1.5rem; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                    <h3 style="font-size: 1.1rem; font-weight: 600; color: #1f2937; margin-bottom: 1rem;">Status Peminjaman</h3>
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="borrowingStatusChart"></canvas>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Chart.defaults.font.family = "'Inter', sans-serif";
                    Chart.defaults.color = '#6b7280';

                    // Trend Data
                    const trendCtx = document.getElementById('borrowingTrendChart').getContext('2d');
                    const trendData = @json($borrowingTrends);

                    new Chart(trendCtx, {
                        type: 'line',
                        data: {
                            labels: trendData.map(item => {
                                const date = new Date(item.date);
                                return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
                            }),
                            datasets: [{
                                label: 'Jumlah Peminjaman',
                                data: trendData.map(item => item.count),
                                borderColor: '#4f46e5',
                                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: '#ffffff',
                                pointBorderColor: '#4f46e5',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: '#1f2937',
                                    padding: 12,
                                    titleFont: { size: 13 },
                                    bodyFont: { size: 13 },
                                    cornerRadius: 6,
                                    displayColors: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: '#f3f4f6',
                                        drawBorder: false
                                    },
                                    ticks: {
                                        stepSize: 1,
                                        font: { size: 11 }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        font: { size: 11 }
                                    }
                                }
                            },
                            interaction: {
                                intersect: false,
                                mode: 'index'
                            }
                        }
                    });

                    // Status Data
                    const statusCtx = document.getElementById('borrowingStatusChart').getContext('2d');
                    const statusCounts = @json($statusCounts);

                    new Chart(statusCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Menunggu', 'Disetujui', 'Dikembalikan', 'Ditolak'],
                            datasets: [{
                                data: [
                                    statusCounts.pending,
                                    statusCounts.approved,
                                    statusCounts.returned,
                                    statusCounts.rejected
                                ],
                                backgroundColor: [
                                    '#f59e0b', // Pending - Orange
                                    '#10b981', // Approved - Green
                                    '#3b82f6', // Returned - Blue
                                    '#ef4444'  // Rejected - Red
                                ],
                                borderWidth: 0,
                                hoverOffset: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                    labels: {
                                        usePointStyle: true,
                                        padding: 20,
                                        font: { size: 12 }
                                    }
                                }
                            },
                            cutout: '70%'
                        }
                    });
                });
            </script>
        @endif
    </div>
@endsection
