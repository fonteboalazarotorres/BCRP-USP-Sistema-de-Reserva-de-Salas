document.addEventListener('DOMContentLoaded', function () {
    // Gráfico de reservas por mês
    const ctxMes = document.getElementById('graficoReservasMes').getContext('2d');
    new Chart(ctxMes, {
        type: 'bar',
        data: {
            labels: reservasPorMes.labels,
            datasets: [{
                label: 'Reservas por mês',
                data: reservasPorMes.data,
                backgroundColor: 'rgba(0, 85, 165, 0.7)',
                borderColor: 'rgba(0, 85, 165, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Gráfico de horários mais reservados
    const ctxHora = document.getElementById('graficoHorarios').getContext('2d');
    new Chart(ctxHora, {
        type: 'line',
        data: {
            labels: horariosMaisReservados.labels,
            datasets: [{
                label: 'Horários mais reservados',
                data: horariosMaisReservados.data,
                fill: false,
                borderColor: 'rgba(0, 85, 165, 1)',
                tension: 0.1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
