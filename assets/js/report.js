import * as M from 'materialize-css';
import $ from 'jquery';
import Chart from 'chart.js';
import pickerOptions from "./pickerOptions";

const createGraph = (ctx, type, labels, data, label, options) => {
  return new Chart(ctx, {
    type: type,
    data: {
      labels: labels,
      datasets: [{
        label: label,
        data: data,
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(153, 102, 255, 0.2)',
        ],
        borderColor: [
          'rgba(255,99,132,1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)',
          'rgba(75, 192, 192, 1)',
          'rgba(153, 102, 255, 1)',
        ],
        borderWidth: 1
      }]
    },
    options: options
  });
};

const displayGraphs = data => {
  const regionChart = $('.status__region-chart');
  const areaChart = $('.status__area-chart');
  const ageChart = $('.status__age-chart');
  const primarySize = 11;
  const secondarySize = 11;
  const generalChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    legend: {
      labels: {
        fontSize: primarySize
      }
    }
  };
  const regionChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    legend: {
      labels: {
        fontSize: primarySize
      }
    },
    tooltips: {
      titleFontSize: secondarySize,
      bodyFontSize: secondarySize
    },
    scales: {
      yAxes: [{
        ticks: {
          beginAtZero:true,
          fontSize: primarySize
        }
      }],
      xAxes: [{
        ticks: {
          fontSize: primarySize
        }
      }]
    }
  };

  createGraph(areaChart, 'pie', ['Dalyviai probleminėse teritorijose', 'Kiti'],
    [ parseInt(data.inProblematicRegionsTotal), parseInt(data.allParticipantsCount) -
    parseInt(data.inProblematicRegionsTotal)], 'Dalyviai pagal teritorijas', generalChartOptions);

  createGraph(ageChart, 'pie', ['Vyresni nei 45', 'Jaunesni nei 45'], [ parseInt(data.olderThan45),
    parseInt(data.allParticipantsCount) - parseInt(data.olderThan45)], 'Dalyvių kiekis pagal amžių',
    generalChartOptions);

  createGraph(regionChart, 'bar', data.inProblematicRegions.map(({ title }) => title),
    data.inProblematicRegions.map(({ participantsCount })=> parseInt(participantsCount)),
    'Apmokytų skaičius', regionChartOptions);
};

export default () => {
  const initialOptions = {
    defaultDate: new Date(),
    setDefaultDate: true,
    ...pickerOptions
  };

  M.Datepicker.init($('.report__date-from'), initialOptions);
  M.Datepicker.init($('.report__date-to'), initialOptions);

  const data = $('.status').data('status');
  data && displayGraphs(data);
};