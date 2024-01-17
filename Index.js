document.addEventListener("DOMContentLoaded", function () {
  fetchDataAndCreateChart();
});

let chartInstance;

const changeZoom = (value) => {
  if (!isNaN(value) && value > 0) {
    console.log("New zoom value:", value);
    fetchDataAndCreateChart(value);
  } else {
    console.log("Invalid zoom value.");
  }
};

async function fetchDataAndCreateChart(val) {
  try {
    const response = await fetch("dummy.json");
    const data = await response.json();
    const sortedNewData = { data: data.data.sort((a, b) => a.time - b.time) };
    const multVal = multiple(val);
    const chartData = processDataForChart(sortedNewData, multVal);
    createChart(chartData, multVal);
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
  const seconds = date.getSeconds();

  const formattedHours = hours < 10 ? `0${hours}` : hours;
  const formattedMinutes = minutes < 10 ? `0${minutes}` : minutes;
  const formattedSeconds = seconds < 10 ? `0${seconds}` : seconds;

  const timeString = `${formattedHours}:${formattedMinutes}:${formattedSeconds}`;

  return timeString;
}

function createChart(chartData, val) {
  const label = chartData.labels.map((i) => i * val);
  console.log(chartData);
  document.getElementById("minTime").value = label[0];
  document.getElementById("maxTime").value = label[label.length - 1];
  const ctx = document.getElementById("myChart").getContext("2d");
  if (chartInstance) {
    chartInstance.destroy();
    console.log("Previous chart destroyed.");
  }
  chartInstance = new Chart(ctx, {
    type: "bar",
    data: {
      labels: label,
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
        x: { type: "linear", position: "bottom" },
      },
    },
  });
}
