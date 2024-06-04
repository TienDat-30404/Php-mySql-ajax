<!DOCTYPE html>
<html>
<head>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
</head>
<body>
    <canvas id="myChart1" style="width:100%;max-width:1000px; margin-bottom : 30px"></canvas>
    <canvas id="myChart2" style="width:100%;max-width:1000px; margin-bottom : 30px;"></canvas>
    <div style = "margin-bottom : 30px;" class = "statistics_1"></div>
    <canvas id="myChart3" style="width:100%;max-width:700px"></canvas>
    <canvas id="myChart4" style="width:100%;max-width:700px"></canvas>
    <div style = "margin-bottom : 30px;" class = "statistics_2"></div>
    <canvas id="myChart5" style="width:100%;max-width: 1000px;"></canvas>
    <div style = "margin-bottom : 30px;" class = "statistics_3"></div>
    <canvas id="myChart6" style="width:100%;max-width: 1000px;"></canvas>
    <div style = "margin-bottom : 30px;" class = "statistics_4"></div>
    <canvas id="myChart7" style="width:100%;max-width: 1000px;"></canvas>
<script>


// Doanh thu bán hàng và biến động theo từng tháng
async function GetRevenueProduct()
{
    var formData = new FormData();
    formData.append('choice', 'statistics_product')
    var response = await fetch(`crud/statistics_api.php`, {
        method : 'POST',
        body : formData
    });
    var json = await response.json();
    var xValues1 = ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"];
    var yValues1 = []
    for(var item in json)
    {   
        yValues1.push(json[item])
    }
    var barColors1 = ["red", "green", "blue", "orange", "brown", "pink", "purple", "yellow", "cyan", "magenta", "lime", "teal"];
    new Chart("myChart1", {
    type: "bar",
    data: {
        labels: xValues1,
        datasets: [{
        backgroundColor: barColors1,
        data: yValues1
        }]
    },
    options: {
        legend: {display: false},
        title: {
        display: true,
        text: "Thống kê doanh thu bán hàng theo từng tháng "
        }
    }
    });



    // Biểu đổ biến động doanh thu bán hàng theo từng tháng
    const xValues = ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"];
    new Chart("myChart2", {
    type: "line",
    data: {
        labels: xValues,
        datasets: [{
        fill: false,
        lineTension: 0,
        backgroundColor: "rgba(0,0,255,1.0)",
        borderColor: "rgba(0,0,255,0.1)",
        data: yValues1
        }]
    },
    options: {
        title: {
        display: true,
        text: "Biểu Đồ Biến Động Doanh Thu Bán Hàng Theo Từng Tháng"
        },
        legend: {display: false},
        scales: {
        yAxes: [{ticks: {
            min: Math.min(...yValues1), 
            max: Math.max(...yValues1)
        }}],
        }
    }
    });
    var tableStatistics_1 = document.createElement('table');
    tableStatistics_1.setAttribute('cellspacing', '0');
    tableStatistics_1.setAttribute('cellpadding', '10');

    var thead = document.createElement('thead');
    thead.innerHTML = `
        <tr>
            ${xValues1.map(month => `<th>${month}</th>`).join('')}
            <th>Doanh thu cả năm</th>
        </tr>
    `;
    tableStatistics_1.appendChild(thead);
    var totalPrice = 0
    var tbody = document.createElement('tbody');
    var row = document.createElement('tr');
    yValues1.forEach(value => {
        var cell = document.createElement('td');
        cell.textContent = value;
        row.appendChild(cell);
        totalPrice = totalPrice + Number(value)
    });
    var cell = document.createElement('td')
    cell.textContent = totalPrice;
    row.appendChild(cell)
    tbody.appendChild(row);
    tableStatistics_1.appendChild(tbody);

    document.querySelector('.statistics_1').appendChild(tableStatistics_1);
}
GetRevenueProduct()



// Thống kê thể loại sản phẩm bán ra ------------------------------------------------------------------
async function GetRevenueCategory()
{
    var formData = new FormData();
    formData.append('choice', 'statistics_category')
    var response = await fetch(`crud/statistics_api.php`, {
        method : 'POST',
        body : formData
    })
    var json = await response.json()
    var dataArray = Object.entries(json);
    dataArray.sort((a, b) => b[1] - a[1]);
    var nameCategory = []
    var revenueCategory = []
    dataArray.forEach(function(value)
    {
        nameCategory.push(value[0])
        revenueCategory.push(value[1])
    })

    var predefinedColors = [
        "#b91d47", "#00aba9", "#2b5797", "#e8c3b9", "#1e7145", "#ff5733", "#33ff57", "#5733ff", "#ff33a1", "#a1ff33"
    ];
    var barColors = nameCategory.map((_, index) => predefinedColors[index % predefinedColors.length]);

    new Chart("myChart3", {
    type: "bar",
    data: {
        labels: nameCategory,
        datasets: [{
        backgroundColor: barColors,
        data: revenueCategory
        }]
    },
    options: {
        legend: {display: false},
        title: {
        display: true,
        text: "Thống kê doanh thu thể loại sản phẩm bán ra "
        }
    }
    });


    new Chart("myChart4", {
    type: "pie",
    data: {
        labels: nameCategory,
        datasets: [{
        backgroundColor: barColors,
        data: revenueCategory
        }]
    },
    options: {
        title: {
        display: true,
        text: "Tỉ lệ doanh thu của các thể loại sản phẩm"
        }
    }
    });
    var tableStatistics_2 = document.createElement('table');
    tableStatistics_2.setAttribute('cellspacing', '0');
    tableStatistics_2.setAttribute('cellpadding', '10');

    var thead = document.createElement('thead');
    thead.innerHTML = `
        <tr>
            ${nameCategory.map(name => `<th>${name}</th>`).join('')}
            <th>Tổng doanh thu</th>
        </tr>
    `;
    tableStatistics_2.appendChild(thead);

    var totalPrice = 0
    var tbody = document.createElement('tbody');
    var row = document.createElement('tr');
    revenueCategory.forEach(value => {
        var cell = document.createElement('td');
        cell.textContent = value;
        row.appendChild(cell);
        totalPrice = totalPrice + Number(value)
    });
    var cell = document.createElement('td')
    cell.textContent = totalPrice
    row.appendChild(cell)
    tbody.appendChild(row);
    tableStatistics_2.appendChild(tbody);

    document.querySelector('.statistics_2').appendChild(tableStatistics_2);
}
GetRevenueCategory()


/* Top 10 sản phẩm bán chạy nhất ---------------------------------------------------------- */
async function GetTop10Product()
{
    var response = await fetch(`crud/statistics_top10Product.php`)
    var json = await response.json()
    var nameProduct = [];
    var totalPrice = []
    for(var item in json)
    {   
        nameProduct.push(item)
        totalPrice.push(json[item])
    }
    var predefinedColors = [
        "pink", "green", "orange",  "purple", "yellow", "brown", "cyan", "blue", "magenta",  "red"
    ];
    var barColors = nameProduct.map((_, index) => predefinedColors[index % predefinedColors.length]);
    new Chart("myChart5", {
    type: "bar",
    data: {
        labels: nameProduct,
        datasets: [{
        backgroundColor: barColors,
        data: totalPrice
        }]
    },
    options: {
        legend: {display: false},
        title: {
        display: true,
        text: "Top 10 sản phẩm bán chạy nhất"
        },
        
    }
    });

    var tableStatistics_3 = document.createElement('table');
    tableStatistics_3.setAttribute('cellspacing', '0');
    tableStatistics_3.setAttribute('cellpadding', '10');

    var thead = document.createElement('thead');
    thead.innerHTML = `
        <tr>
            ${nameProduct.map(name => `<th style = "font-size : 12px">${name}</th>`).join('')}
        </tr>
    `;
    tableStatistics_3.appendChild(thead);

    var tbody = document.createElement('tbody');
    var row = document.createElement('tr');
    totalPrice.forEach(value => {
        var cell = document.createElement('td');
        cell.textContent = value;
        row.appendChild(cell);
        totalPrice = totalPrice + Number(value)
    });
    tbody.appendChild(row);
    tableStatistics_3.appendChild(tbody);

    document.querySelector('.statistics_3').appendChild(tableStatistics_3);
}
GetTop10Product()


// Profit month_1 -> month_12 ----------------------------------------------------------
async function GetProfitEachMonth()
{
    var response = await fetch(`crud/statistics_profit.php`)
    var json = await response.json();
    const xValues = ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"];
    var profit = []
    for(var item in json)
    {
        profit.push(json[item].profit)
    }
    new Chart("myChart6", {
    type: "line",
    data: {
        labels: xValues,
        datasets: [{
        fill: false,
        lineTension: 0,
        backgroundColor: "rgba(0,0,255,1.0)",
        borderColor: "rgba(0,0,255,0.1)",
        data: profit
        }]
    },
    options: {
        title: {
        display: true,
        text: "Biểu Đồ Biến Động lợi nhuận Bán Hàng Theo Từng Tháng"
        },
        legend: {display: false},
        scales: {
        yAxes: [{ticks: {
            min: Math.min(...profit), 
            max: Math.max(...profit)
        }}],
        }
    }
    });


    var tableStatistics_4 = document.createElement('table');
    tableStatistics_4.setAttribute('cellspacing', '0');
    tableStatistics_4.setAttribute('cellpadding', '10');

    var thead = document.createElement('thead');
    thead.innerHTML = `
        <tr>
            ${xValues.map(month => `<th>${month}</th>`).join('')}
            <th>Tổng lợi nhuân</th>
        </tr>
    `;
    tableStatistics_4.appendChild(thead);

    var totalPrice = 0
    var tbody = document.createElement('tbody');
    var row = document.createElement('tr');
    profit.forEach(value => {
        var cell = document.createElement('td');
        cell.textContent = value;
        row.appendChild(cell);
        totalPrice = totalPrice + Number(value)
    });
    var cell = document.createElement('td')
    cell.textContent = totalPrice
    row.appendChild(cell)
    tbody.appendChild(row);
    tableStatistics_4.appendChild(tbody);

    document.querySelector('.statistics_4').appendChild(tableStatistics_4);
}
GetProfitEachMonth()

async function GetTop10Staff()
{
    var response = await fetch(`crud/statistics_top10Staff.php`)
    var json = await response.json()
    console.log(json)
    var dataArray = Object.entries(json);
    dataArray.sort((a, b) => b[1] - a[1]);
    var nameStaff = []
    var totalPrice = []
    dataArray.forEach(function(value)
    {
        nameStaff.push(value[0])
        totalPrice.push(value[1])
    })
    var predefinedColors = [
        "pink", "green", "orange",  "purple", "yellow", "brown", "cyan", "blue", "magenta",  "red"
    ];
    var barColors = nameStaff.map((_, index) => predefinedColors[index % predefinedColors.length]);
    new Chart("myChart7", {
    type: "bar",
    data: {
        labels: nameStaff,
        datasets: [{
        backgroundColor: barColors,
        data: totalPrice
        }]
    },
    options: {
        legend: {display: false},
        title: {
        display: true,
        text: "Top 10 nhân viên chạy KPI tốt nhất"
        },
        
    }
    });

}
GetTop10Staff()
</script>

</body>
</html>