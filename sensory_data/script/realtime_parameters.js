// Function to get the number of days in the current month
function getDaysInMonth() {
    let date = new Date();
    let year = date.getFullYear();
    let month = date.getMonth() + 1; // JS months are 0-based
    let days = new Date(year, month, 0).getDate();
    let labels = [];
    for (let i = 1; i <= days; i++) {
        labels.push(i);
    }
    return labels;
}

var options = {
    series: [{
        name: 'Temperature_01 (°C)',
        data: [31, 30, 28, 29, 32, 35, 34, 33, 31, 30, 29, 32, 34, 30, 29, 28, 27, 26, 29, 32, 31, 30, 29, 28, 27, 26, 25, 24, 30, 32, 31] // Replace with actual data
    }, {
        name: 'Temperature_02 (°C)',
        data: [29, 28, 30, 31, 33, 32, 31, 30, 28, 29, 30, 32, 33, 31, 30, 28, 27, 26, 25, 28, 29, 30, 31, 32, 34, 33, 31, 30, 28, 29, 27] // Replace with actual data
    }, {
        name: 'Humidity (%)',
        data: [11, 32, 45, 32, 34, 52, 41, 39, 38, 37, 36, 35, 34, 32, 30, 28, 26, 25, 24, 23, 22, 20, 19, 18, 17, 16, 15, 14, 13, 12, 11] // Replace with actual data
    }, {
        name: 'Water Flow (L/min)',
        data: [11, 25, 60, 100, 25, 42, 45, 30, 28, 32, 45, 50, 60, 42, 40, 39, 37, 35, 34, 30, 28, 27, 25, 20, 18, 16, 15, 14, 13, 12, 11] // Replace with actual data
    }],
    chart: {
        height: 400,
        type: 'area',
        background: '#1a1a1a', // Dark background
        foreColor: '#ffffff' // White text
    },
    colors: ['#417630', '#365b25', '#2a4720', '#4d7c3d'], // More green shades
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth',
        width: 2
    },
    grid: {
        borderColor: '#444',
        strokeDashArray: 5
    },
    xaxis: {
        categories: getDaysInMonth(), // Dynamically generated days
        labels: {
            style: {
                colors: '#F8F8F8' // Off white text
            }
        }
    },
    yaxis: {
        labels: {
            style: {
                colors: '#F8F8F8' // White text
            }
        }
    },
    tooltip: {
        theme: "dark"
    },
    fill: {
        type: 'gradient',
        gradient: {
            shade: 'dark',
            type: "vertical",
            gradientToColors: ['#365b25', '#2a4720', '#4d7c3d'],
            stops: [0, 100]
        }
    },
    legend: {
        labels: {
            colors: '#ffffff'
        }
    }
};

var chart = new ApexCharts(document.querySelector("#chart"), options);
chart.render();