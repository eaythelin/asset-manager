import { Chart, registerables } from "chart.js";
Chart.register(...registerables);

fetch('/dashboard/chart-data')
  .then(response => response.json())
  .then(data => {

    document.getElementById('chartLoading').classList.add('hidden');
    document.getElementById('categoryChart').classList.remove('hidden')
    const ctx = document.getElementById("categoryChart").getContext("2d");
    const myChart = new Chart(ctx, {
      type:"doughnut",
      data:{
        labels: data.labels,
        datasets:[{
          label:" Assets",
          data: data.counts,
          backgroundColor:['red','blue','yellow','orange','green'],
        }]
      },
      options:{
        responsive:true,
        maintainAspectRatio:false,
        plugins:{
          title:{
            display:true,
            text:'Assets by Category',
            font:{
              size:15,
              weight:"bold"
            }
          },legend:{
            display:true,
            position:"bottom",
            align:"center"
          }
        }
      }
    });
  })
  .catch(error => console.error('Error fetching chart data:', error))