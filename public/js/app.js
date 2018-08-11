function chartview(period=7, viewtype){
	var today = new Date().toISOString().split('T')[0];
	chartview.period = period;
	chartview.viewtype = viewtype;
 $.ajax({
   url : "/sensor/historical/view",
   type : "GET",
   data : {"date": today, "period": period},
   datatype : "application/json",
   success : function(data){
     console.log(data);
//     data = JSON.parse(data);
	 $("canvas").attr("id", chartview.viewtype+"canvas");
     var timestamp = [];
     var CO = [];
     var SO2 = [];
     var NO2 = [];
     var O3 = [];
     var PM2_5 = [];
     var temp = [];
	 var heart = [];
     var COAQI = [];
     var SO2AQI = [];
     var NO2AQI = [];
     var O3AQI = [];
     var PM2_5AQI = [];
	 var TOTALAQI = [];


     for(var i in data) {

       timestamp.push(data[i].date);
       CO.push(data[i].co);
       SO2.push(data[i].so2);
       NO2.push(data[i].no2);
       O3.push(data[i].o3);
       PM2_5.push(data[i].pm25);
       temp.push(data[i].temp);
	   heart.push(data[i].heart);
       COAQI.push(data[i].coaqi);
       SO2AQI.push(data[i].so2aqi);
       NO2AQI.push(data[i].no2aqi);
       O3AQI.push(data[i].o3aqi);
       PM2_5AQI.push(data[i].pm25aqi);
	   TOTALAQI.push(data[i].totalaqi);
     }
	 var codata = {
	       labels: timestamp,
	       datasets: [
	         {
	           label: "CO",
	           fill: false,
	           lineTension: 0.1,
	           backgroundColor: "rgba(102, 51, 255, 0.75)",
	           borderColor: "rgba(102, 51, 255, 1)",
	           pointHoverBackgroundColor: "rgba(102, 51, 255, 1)",
	           pointHoverBorderColor: "rgba(102, 51, 255, 1)",
	           data: CO
	         }
	       ]
	     };

		var cdt = $("#cocanvas");

	     var LineGraph = new Chart(cdt, {
	       type: 'line',
	       data: codata,
			options : {
			      scales: {
			        yAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Concentrate'
			          }
			        }],
			        xAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Date'
			          }
			        }]
			      }
			    }
	     });

	var so2data = {
	       labels: timestamp,
	       datasets: [
	         {
	           label: "SO2",
	           fill: false,
	           lineTension: 0.1,
	           backgroundColor: "rgba(211, 72, 54, 0.75)",
	           borderColor: "rgba(211, 72, 54, 1)",
	           pointHoverBackgroundColor: "rgba(211, 72, 54, 1)",
	           pointHoverBorderColor: "rgba(211, 72, 54, 1)",
	           data: SO2,
	         }	         
	       ]
	     };

		var sdt = $("#so2canvas");

	     var LineGraph = new Chart(sdt, {
	       type: 'line',
	       data: so2data,
			options : {
			      scales: {
			        yAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Concentrate'
			          }
			        }],
			        xAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Date'
			          }
			        }]
			      }
			    }
	     });

	var no2data = {
	       labels: timestamp,
	       datasets: [
	         {
	           label: "NO2",
	           fill: false,
	           lineTension: 0.1,
	           backgroundColor: "rgba(59, 89, 152, 0.75)",
	           borderColor: "rgba(59, 89, 152, 1)",
	           pointHoverBackgroundColor: "rgba(59, 89, 152, 1)",
	           pointHoverBorderColor: "rgba(59, 89, 152, 1)",
	           data: NO2
	         }	         
	       ]
	     };

		var ndt = $("#no2canvas");

	     var LineGraph = new Chart(ndt, {
	       type: 'line',
	       data: no2data,
			options : {
			      scales: {
			        yAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Concentrate'
			          }
			        }],
			        xAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Date'
			          }
			        }]
			      }
			    }
	     });

		var o3data = {
	       labels: timestamp,
	       datasets: [
	         {
	           label: "O3",
	           fill: false,
	           lineTension: 0.1,
	           backgroundColor: "rgba(29, 202, 255, 0.75)",
	           borderColor: "rgba(29, 202, 255, 1)",
	           pointHoverBackgroundColor: "rgba(29, 202, 255, 1)",
	           pointHoverBorderColor: "rgba(29, 202, 255, 1)",
	           data: O3
	         }	         
	       ]
	     };

		var odt = $("#o3canvas");

	     var LineGraph = new Chart(odt, {
	       type: 'line',
	       data: o3data,
			options : {
			      scales: {
			        yAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Concentrate'
			          }
			        }],
			        xAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Date'
			          }
			        }]
			      }
			    }
	     });

	var pm25data = {
	       labels: timestamp,
	       datasets: [
	         {
	           label: "PM2.5",
	           fill: false,
	           lineTension: 0.1,
	           backgroundColor: "rgba(255, 153, 0, 0.75)",
	           borderColor: "rgba(255, 153, 0, 1)",
	           pointHoverBackgroundColor: "rgba(255, 153, 0, 1)",
	           pointHoverBorderColor: "rgba(255, 153, 0, 1)",
	           data: PM2_5
	         }	         
	       ]
	     };

		var pdt = $("#pm25canvas");

	     var LineGraph = new Chart(pdt, {
	       type: 'line',
	       data: pm25data,
			options : {
			      scales: {
			        yAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Concentrate'
			          }
			        }],
			        xAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Date'
			          }
			        }]
			      }
			    }
	     });

		var tempdata = {
	       labels: timestamp,
	       datasets: [
	         {
	           label: "temp",
	           fill: false,
	           lineTension: 0.1,
	           backgroundColor: "rgba(51, 204, 101, 0.75)",
	           borderColor: "rgba(51, 204, 101, 1)",
	           pointHoverBackgroundColor: "rgba(51, 204, 101, 1)",
	           pointHoverBorderColor: "rgba(51, 204, 101, 1)",
	           data: temp
	         }	         
	       ]
	     };

		var tdt = $("#tempcanvas");

	     var LineGraph = new Chart(tdt, {
	       type: 'line',
	       data: tempdata,
			options : {
			      scales: {
			        yAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Temperature'
			          }
			        }],
			        xAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Date'
			          }
			        }]
			      }
			    }
	     });

	 var heartdata = {
	       labels: timestamp,
	       datasets: [
	         {
	           label: "HEARTRATE",
	           fill: false,
	           lineTension: 0.1,
	           backgroundColor: "rgba(255, 153, 153, 0.75)",
	           borderColor: "rgba(255, 153, 153, 1)",
	           pointHoverBackgroundColor: "rgba(255, 153, 153, 1)",
	           pointHoverBorderColor: "rgba(255, 153, 153, 1)",
	           data: heart
	         }	         
	       ]
	     };

		var hdt = $("#heartcanvas");

	     var LineGraph = new Chart(hdt, {
	       type: 'line',
	       data: heartdata,
			options : {
			      scales: {
			        yAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Heartrate'
			          }
			        }],
			        xAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Date'
			          }
			        }]
			      }
			    }
	     });

	 var coaqidata = {
	       labels: timestamp,
	       datasets: [
	         {
	           label: "COAQI",
	           fill: false,
	           lineTension: 0.1,
	           backgroundColor: "rgba(102, 51, 255, 0.75)",
	           borderColor: "rgba(102, 51, 255, 1)",
	           pointHoverBackgroundColor: "rgba(102, 51, 255, 1)",
	           pointHoverBorderColor: "rgba(102, 51, 255, 1)",
	           data: COAQI
	         }	         
	       ]
	     };

		var cdta = $("#coaqicanvas");

	     var LineGraph = new Chart(cdta, {
	       type: 'line',
	       data: coaqidata,
			options : {
			      scales: {
			        yAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'AQI'
			          }
			        }],
			        xAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Date'
			          }
			        }]
			      }
			    }
	     });

	 var so2aqidata = {
	       labels: timestamp,
	       datasets: [
	         {
	           label: "SO2AQI",
	           fill: false,
	           lineTension: 0.1,
	           backgroundColor: "rgba(211, 72, 54, 0.75)",
	           borderColor: "rgba(211, 72, 54, 1)",
	           pointHoverBackgroundColor: "rgba(211, 72, 54, 1)",
	           pointHoverBorderColor: "rgba(211, 72, 54, 1)",
	           data: SO2AQI
	         }	         
	       ]
	     };

		var sdta = $("#so2aqicanvas");

	     var LineGraph = new Chart(sdta, {
	       type: 'line',
	       data: so2aqidata,
			options : {
			      scales: {
			        yAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'AQI'
			          }
			        }],
			        xAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Date'
			          }
			        }]
			      }
			    }
	     });

	 var no2aqidata = {
	       labels: timestamp,
	       datasets: [
	         {
	           label: "NO2AQI",
	           fill: false,
	           lineTension: 0.1,
	           backgroundColor: "rgba(59, 89, 152, 0.75)",
	           borderColor: "rgba(59, 89, 152, 1)",
	           pointHoverBackgroundColor: "rgba(59, 89, 152, 1)",
	           pointHoverBorderColor: "rgba(59, 89, 152, 1)",
	           data: NO2AQI
	         }	         
	       ]
	     };

		var ndta = $("#no2aqicanvas");

	     var LineGraph = new Chart(ndta, {
	       type: 'line',
	       data: no2aqidata,
			options : {
			      scales: {
			        yAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'AQI'
			          }
			        }],
			        xAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Date'
			          }
			        }]
			      }
			    }
	     });

	 var o3aqidata = {
	       labels: timestamp,
	       datasets: [
	         {
	           label: "O3AQI",
	           fill: false,
	           lineTension: 0.1,
	           backgroundColor: "rgba(29, 202, 255, 0.75)",
	           borderColor: "rgba(29, 202, 255, 1)",
	           pointHoverBackgroundColor: "rgba(29, 202, 255, 1)",
	           pointHoverBorderColor: "rgba(29, 202, 255, 1)",
	           data: O3AQI
	         }	         
	       ]
	     };

		var odta = $("#o3aqicanvas");

	     var LineGraph = new Chart(odta, {
	       type: 'line',
	       data: o3aqidata,
			options : {
			      scales: {
			        yAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'AQI'
			          }
			        }],
			        xAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Date'
			          }
			        }]
			      }
			    }
	     });

	 var pm25aqidata = {
	       labels: timestamp,
	       datasets: [
	         {
	           label: "PM2_5AQI",
	           fill: false,
	           lineTension: 0.1,
	           backgroundColor: "rgba(211, 72, 54, 0.75)",
	           borderColor: "rgba(211, 72, 54, 1)",
	           pointHoverBackgroundColor: "rgba(211, 72, 54, 1)",
	           pointHoverBorderColor: "rgba(211, 72, 54, 1)",
	           data: PM2_5AQI
	         }	         
	       ]
	     };

		var pdta = $("#pm25aqicanvas");

	     var LineGraph = new Chart(pdta, {
	       type: 'line',
	       data: pm25aqidata,
			options : {
			      scales: {
			        yAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'AQI'
			          }
			        }],
			        xAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Date'
			          }
			        }]
			      }
			    }
	     });

	 var totalaqidata = {
	       labels: timestamp,
	       datasets: [
	         {
	           label: "TOTALAQI",
	           fill: false,
	           lineTension: 0.1,
	           backgroundColor: "rgba(153, 153, 0, 0.75)",
	           borderColor: "rgba(153, 153, 0, 1)",
	           pointHoverBackgroundColor: "rgba(153, 153, 0, 1)",
	           pointHoverBorderColor: "rgba(153, 153, 0, 1)",
	           data: TOTALAQI
	         }	         
	       ]
	     };

		var tdta = $("#totalaqicanvas");

	     var LineGraph = new Chart(tdta, {
	       type: 'line',
	       data: totalaqidata,
			options : {
			      scales: {
			        yAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'AQI'
			          }
			        }],
			        xAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Date'
			          }
			        }]
			      }
			    }
	     });



   },
   error : function(data) {
   }
 });
}














$(document).ready(function(){
	chartview.period=7;
	chartview.viewtype='no2';
 $.ajax({
   url : "/sensor/historical/view",
   type : "GET",
   data : {"date": "2018-08-10", "period": "7"},
   datatype : "application/json",
   success : function(data){
     console.log(data);
//     data = JSON.parse(data);

     var timestamp = [];
     var CO = [];
     var SO2 = [];
     var NO2 = [];
     var O3 = [];
     var PM2_5 = [];
     var temp = [];


     for(var i in data) {

       timestamp.push(data[i].date);
       CO.push(data[i].co);
       SO2.push(data[i].so2);
       NO2.push(data[i].no2);
       O3.push(data[i].o3);
       PM2_5.push(data[i].pm25);
       temp.push(data[i].temp);
     }
	 var codata = {
	       labels: timestamp,
	       datasets: [
	         {
	           label: "CO",
	           fill: false,
	           lineTension: 0.1,
	           backgroundColor: "rgba(59, 89, 152, 0.75)",
	           borderColor: "rgba(59, 89, 152, 1)",
	           pointHoverBackgroundColor: "rgba(59, 89, 152, 1)",
	           pointHoverBorderColor: "rgba(59, 89, 152, 1)",
	           data: CO
	         }	         
	       ]
	     };

		var cdt = $("#cocanvas");

	     var LineGraph = new Chart(cdt, {
	       type: 'line',
	       data: codata
	     });

	var so2data = {
	       labels: timestamp,
	       datasets: [
	         {
	           label: "SO2",
	           fill: false,
	           lineTension: 0.1,
	           backgroundColor: "rgba(211, 72, 54, 0.75)",
	           borderColor: "rgba(211, 72, 54, 1)",
	           pointHoverBackgroundColor: "rgba(211, 72, 54, 1)",
	           pointHoverBorderColor: "rgba(211, 72, 54, 1)",
	           data: SO2,
	         }	         
	       ]
	     };

		var sdt = $("#so2canvas");

	     var LineGraph = new Chart(sdt, {
	       type: 'line',
	       data: so2data
	     });

	var no2data = {
	       labels: timestamp,
	       datasets: [
	         {
	           label: "NO2",
	           fill: false,
	           lineTension: 0.1,
	           backgroundColor: "rgba(59, 89, 152, 0.75)",
	           borderColor: "rgba(59, 89, 152, 1)",
	           pointHoverBackgroundColor: "rgba(59, 89, 152, 1)",
	           pointHoverBorderColor: "rgba(59, 89, 152, 1)",
	           data: NO2
	         }	         
	       ]
	     };

		var ndt = $("#no2canvas");

	     var LineGraph = new Chart(ndt, {
	       type: 'line',
	       data: no2data,
			options : {
			      scales: {
			        yAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Concentrate'
			          }
			        }],
			        xAxes: [{
			          scaleLabel: {
			            display: true,
			            labelString: 'Date'
			          }
			        }]
			      }
			    }
	     });

		var o3data = {
	       labels: timestamp,
	       datasets: [
	         {
	           label: "O3",
	           fill: false,
	           lineTension: 0.1,
	           backgroundColor: "rgba(29, 202, 255, 0.75)",
	           borderColor: "rgba(29, 202, 255, 1)",
	           pointHoverBackgroundColor: "rgba(29, 202, 255, 1)",
	           pointHoverBorderColor: "rgba(29, 202, 255, 1)",
	           data: O3
	         }	         
	       ]
	     };

		var odt = $("#o3canvas");

	     var LineGraph = new Chart(odt, {
	       type: 'line',
	       data: o3data
	     });

	var pm25data = {
	       labels: timestamp,
	       datasets: [
	         {
	           label: "PM2.5",
	           fill: false,
	           lineTension: 0.1,
	           backgroundColor: "rgba(211, 72, 54, 0.75)",
	           borderColor: "rgba(211, 72, 54, 1)",
	           pointHoverBackgroundColor: "rgba(211, 72, 54, 1)",
	           pointHoverBorderColor: "rgba(211, 72, 54, 1)",
	           data: PM2_5
	         }	         
	       ]
	     };

		var pdt = $("#pm25canvas");

	     var LineGraph = new Chart(pdt, {
	       type: 'line',
	       data: pm25data
	     });

		var tempdata = {
	       labels: timestamp,
	       datasets: [
	         {
	           label: "temp",
	           fill: false,
	           lineTension: 0.1,
	           backgroundColor: "rgba(211, 72, 54, 0.75)",
	           borderColor: "rgba(211, 72, 54, 1)",
	           pointHoverBackgroundColor: "rgba(211, 72, 54, 1)",
	           pointHoverBorderColor: "rgba(211, 72, 54, 1)",
	           data: temp
	         }	         
	       ]
	     };

		var tdt = $("#tempcanvas");

	     var LineGraph = new Chart(tdt, {
	       type: 'line',
	       data: tempdata
	     });

   },
   error : function(data) {
   }
 });
});
