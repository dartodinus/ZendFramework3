$(document).ready(function() {
    var MONTHS=["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var config= {
        type:'line', data: {
            labels:["January", "February", "March", "April", "May", "June", "July"], datasets:[ 
                {
                    label: "EMKL", backgroundColor: window.chartColors.blue, borderColor: window.chartColors.blue, data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()], fill: false,
                }, 
                {
                    label: "CONTAINER", fill: false, backgroundColor: window.chartColors.purple, borderColor: window.chartColors.purple, data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()],
                },
                {
                    label: "UOI", backgroundColor: window.chartColors.yellow, borderColor: window.chartColors.yellow, data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()], fill: false,
                }, 
                {
                    label: "CY BELAWAN", fill: false, backgroundColor: window.chartColors.orange, borderColor: window.chartColors.orange, data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()],
                }
            ]
        }
        , options: {
            responsive:true, title: {
                display: true, text: 'SHIPMENT TREND'
            }
            , tooltips: {
                mode: 'index', intersect: false,
            }
            , hover: {
                mode: 'nearest', intersect: true
            }
            , scales: {
                xAxes:[ {
                    display:true, scaleLabel: {
                        display: true, labelString: 'Month'
                    }
                }
                ], yAxes:[ {
                    display:true, scaleLabel: {
                        display: true, labelString: ''
                    }
                }
                ]
            }
        }
    }
    ;
    var ctx=document.getElementById("chartjs_line").getContext("2d");
    window.myLine=new Chart(ctx, config);
}

);
$(document).ready(function() {
	var randomScalingFactor = function() {
        return Math.round(Math.random() * 100);
    };

    var config = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                    randomScalingFactor(),
                ],
                backgroundColor: [
                    window.chartColors.red,
                    window.chartColors.orange,
                    window.chartColors.yellow,
                    window.chartColors.green,
                    window.chartColors.blue,
                ],
                label: 'Dataset 1'
            }],
            labels: [
                "Red",
                "Orange",
                "Yellow",
                "Green",
                "Blue"
            ]
        },
        options: {
            responsive: true,
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Doughnut Chart'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    };

        var ctx = document.getElementById("chartjs_doughnut").getContext("2d");
        window.myDoughnut = new Chart(ctx, config);
    
	});