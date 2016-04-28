import Chart from 'chart.js';

module.exports = function (Report) {

    Chart.defaults.global.legend.position = 'bottom';

    let ctx = document.getElementById("ua-report").getContext("2d");
    let data = {
        datasets: [{
            data: [
                Report.desktop,
                Report.mobile,
                Report.tablet,
                Report.robots,
                Report.other,
                Report.unknown
            ],
            backgroundColor: [
                "#001f3f",
                "#39CCCC",
                "#FF4136",
                "#B10DC9",
                "#AAAAAA",
                "#DDDDDD"
            ],
            label: 'Report'
        }],
        labels: [
            "Desktop",
            "Mobile",
            "Tablet",
            "Robots",
            "Other",
            "Unkown"
        ]
    };

    new Chart(ctx, {
        data: data,
        type: 'pie',
        options: {}
    });

}