document.addEventListener("DOMContentLoaded", function () {
  fetchDataAndCreateChart();
});

let chartInstance;
let DataTable;

const changeZoom = (value) => {
  if (!isNaN(value) && value > 0) {
    fetchDataAndCreateChart(value);
  } else {
    console.log("Invalid zoom value.");
  }
};

const changeMinMaxTime = () => {
  const minTime = timeStringToTimestamp(
    document.getElementById("minTime").value
  );
  const maxTime = timeStringToTimestamp(
    document.getElementById("maxTime").value
  );
  const value = document.getElementById("densityVal").value;
  if (!isNaN(minTime) && !isNaN(maxTime)) {
    fetchDataAndCreateChart(value, minTime, maxTime);
  } else {
    fetchDataAndCreateChart(value);
  }
};

const filterData = (data, minTime, maxTime) => {
  if (!minTime || !maxTime) {
    return data;
  }
  return data.filter((entry) => {
    return entry.time >= minTime && entry.time <= maxTime;
  });
};

async function fetchDataAndCreateChart(val, minTime, maxTime) {
  try {
    const response = await fetch("dummy.json");
    const data = await response.json();
    const filteredData = filterData(data.data, minTime, maxTime);
    const sortedNewData = {
      data: filteredData.sort((a, b) => a.time - b.time),
    };
    const multVal = multiple(val);
    const chartData = processDataForChart(sortedNewData, multVal);
    createChart(chartData, multVal, sortedNewData);
    
    // Update DataTable with sortedNewData
    updateDataTable(filteredData);
  } catch (error) {
    console.error("Error fetching data:", error);
  }
}

const multiple = (input) => (input ? input * 60 * 1000 : 10 * 60 * 1000);

function processDataForChart(data, val) {
  const groupedData = data.data.reduce((acc, entry) => {
    const timeInMinutes = Math.floor(entry.time / val);
    const status = entry.status ? "true" : "false";

    if (!acc[timeInMinutes]) {
      acc[timeInMinutes] = { true: 0, false: 0 };
    }

    acc[timeInMinutes][status] += 1;

    return acc;
  }, {});

  const labels = Object.keys(groupedData);
  const trueCounts = labels.map((label) => groupedData[label].true);
  const falseCounts = labels.map((label) => groupedData[label].false);

  return { labels, trueCounts, falseCounts };
}

function timestampToTime(timestamp) {
  const date = new Date(timestamp);
  const hours = date.getHours();
  const minutes = date.getMinutes();
  const formattedMinutes = minutes < 10 ? `0${minutes}` : minutes;
  const day = date.getDate();
  const month = date.getMonth() + 1;
  const year = date.getFullYear();
  const timeString = `${day}/${month}/${year} ${hours}:${formattedMinutes}`;
  return timeString;
}

function timeStringToTimestamp(timeString) {
  const [datePart, timePart] = timeString.split(" ");
  const [day, month, year] = datePart.split("/");
  const [hours, minutes] = timePart.split(":");
  const dateObject = new Date(year, month - 1, day, hours, minutes);
  const timestamp = dateObject.getTime();
  return timestamp;
}

function createChart(chartData, val, sortedNewData) {
  const label = chartData.labels.map((i) => i * val);
  document.getElementById("minTime").value = timestampToTime(label[0], "full");
  document.getElementById("maxTime").value = timestampToTime(
    label[label.length - 1],
    "full"
  );
  const ctx = document.getElementById("myChart").getContext("2d");
  if (chartInstance) {
    chartInstance.destroy();
  }
  chartInstance = new Chart(ctx, {
    type: "line",
    data: {
      labels: label.map(timestampToTime),
      datasets: [
        {
          label: "True",
          data: chartData.trueCounts,
          backgroundColor: "rgba(75, 192, 192, 0.2)",
          borderColor: "rgba(75, 192, 192, 1)",
          borderWidth: 1,
        },
        {
          label: "False",
          data: chartData.falseCounts,
          backgroundColor: "rgba(255, 99, 132, 0.2)",
          borderColor: "rgba(255, 99, 132, 1)",
          borderWidth: 1,
        },
      ],
    },
    options: {
      scales: {
        x: [
          {
            type: "time",
            time: {
              unit: "minute",
              displayFormats: {
                minute: "HH:mm",
              },
            },
            position: "bottom",
          },
        ],
      },
    },
  });
}

function updateDataTable(sortedNewData) {
  if (DataTable) {
    DataTable.destroy();
  }

  console.log(sortedNewData);

  DataTable = $('#example').DataTable({
    data: sortedNewData,
    columns: [
      { data: "user" },
      { data: "email" },
      { data: "id" },
      {
        data: "time",
        render: function (data) {
          return new Date(data).toLocaleString();
        },
      },
      {
        data: "status",
        render: function (data) {
          return data ? "Active" : "Inactive";
        },
      },
    ],
  });
}
