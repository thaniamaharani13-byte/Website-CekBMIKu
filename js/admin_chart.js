document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // GRAFIK 1: DISTRIBUSI KATEGORI BMI (Doughnut Chart)
    // ==========================================
    
    // Ambil elemen data tersembunyi
    const labelsElement = document.getElementById('bmi-chart-labels');
    const dataElement = document.getElementById('bmi-chart-data');
    const ctx = document.getElementById('bmiDistributionChart');

    if (labelsElement && dataElement && ctx) {
        try {
            // Parsing data JSON dari atribut data-content
            const labels = JSON.parse(labelsElement.dataset.content);
            const rawData = JSON.parse(dataElement.dataset.content);
            
            // Konversi data string menjadi Number
            const data_counts = rawData.map(Number);
            
            function getColorByCategory(label) {
                switch(label.toLowerCase()) {
                    case 'kurus':
                        return '#4a8ef5'; // Biru
                    case 'normal':
                        return '#28a745'; // Hijau
                    case 'gemuk':
                        return '#ffc107'; // Kuning
                    case 'obesitas':
                        return '#dc3545'; // Merah
                    default:
                        return '#6c757d'; // Abu-abu
                }
            }

            const backgroundColors = labels.map(getColorByCategory);
            
            // Cek apakah ada data untuk digambar
            if (data_counts && data_counts.length > 0) {
                new Chart(ctx, {
                    type: 'doughnut', 
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Riwayat Pengecekan',
                            data: data_counts,
                            backgroundColor: backgroundColors,
                            hoverOffset: 10
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'right' },
                            title: { display: false }
                        }
                    }
                });
            } else {
                // Tampilkan pesan jika data kosong
                ctx.parentNode.innerHTML = '<p class="text-center text-muted pt-5">Belum ada riwayat pengecekan BMI untuk ditampilkan.</p>';
            }

        } catch (e) {
            console.error("Gagal memuat atau menggambar grafik Distribusi BMI:", e);
        }
    }
});