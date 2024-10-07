     // Biểu đồ đường
     const ctxLine = document.getElementById('lineChart').getContext('2d');
     const lineChart = new Chart(ctxLine, {
         type: 'line',
         data: {
             labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
             datasets: [{
                 label: 'Số lượng người dùng',
                 data: [65, 59, 80, 81, 56, 55, 40],
                 fill: false,
                 borderColor: 'rgba(75, 192, 192, 1)',
                 tension: 0.1
             }]
         },
         options: {
             responsive: true,
             scales: {
                 y: {
                     beginAtZero: true
                 }
             }
         }
     });

     // Biểu đồ tròn
     const ctxPie = document.getElementById('pieChart').getContext('2d');
     const pieChart = new Chart(ctxPie, {
         type: 'pie',
         data: {
             labels: ['Red', 'Blue', 'Yellow'],
             datasets: [{
                 label: 'Số lượng',
                 data: [300, 50, 100],
                 backgroundColor: [
                     'rgba(255, 99, 132, 0.2)',
                     'rgba(54, 162, 235, 0.2)',
                     'rgba(255, 206, 86, 0.2)'
                 ],
                 borderColor: [
                     'rgba(255, 99, 132, 1)',
                     'rgba(54, 162, 235, 1)',
                     'rgba(255, 206, 86, 1)'
                 ],
                 borderWidth: 1
             }]
         },
         options: {
             responsive: true,
         }
     });