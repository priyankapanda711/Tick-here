import { loadNavbar } from "../Admin/components/admin_navbar/navbar.js";
declare var ApexCharts: any;

document.addEventListener("DOMContentLoaded", () => {
  loadNavbar();

  const options = {
    chart: {
      type: "bar",
      height: 260,
      toolbar: { show: false },
      background: "transparent",
      foreColor: "#333",
    },
    grid: {
      show: true,
    },
    plotOptions: {
      bar: {
        borderRadius: 4,
        columnWidth: "30%",
        distributed: true,
        dataLabels: {
          position: "middle", // show value on top
        },
      },
    },

    states: {
      normal: {
        filter: {
          type: "none",
        },
      },
      hover: {
        filter: {
          type: "darken",
          value: 1,
        },
      },
      active: {
        filter: {
          type: "none",
        },
      },
    },
    fill: {
      type: "solid",
      colors: [
        "#D6BCFA",
        "#B794F4",
        "#9F7AEA",
        "#805AD5",
        "#6B46C1",
        "#553C9A",
      ],
    },
    tooltip: {
      theme: "light",
    },

    dataLabels: {
      enabled: false,
      formatter: (val: number) => val.toString(),
      offsetY: -12,
      style: {
        fontSize: "12px",
        colors: ["#45006E"], // purple tone
      },
    },
    series: [
      {
        name: "Event Bookings",
        data: [130, 140, 150, 150, 160, 170],
      },
    ],
    xaxis: {
      categories: [
        "Comedy",
        "Kids",
        "Music",
        "Workshop",
        "Performance",
        "Sports",
      ],
      labels: {
        style: {
          fontSize: "12px",
          colors: "#6B7280", // Tailwind gray-500
        },
      },
      axisTicks: { show: false },
      axisBorder: { show: false },
    },
    yaxis: {
      show: true,
    },
   
  };

  const chart = new ApexCharts(document.querySelector("#myChart"), options);
  chart.render();


  //fetch
  const createEventButton = document.getElementById("addEventButton");
  if (createEventButton) {
    createEventButton.addEventListener("click", () => {
      window.location.href = "/admin/create_event/";
    });
  }
});
