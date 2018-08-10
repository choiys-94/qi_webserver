$(document).ready(function(){
	updateAqi();
});

function updateAqi () {
 $.ajax({
   url : "/sensor/aqi/view",
   type : "GET",
   datatype : "application/json",
   cache: false,
   success : function(data){
     console.log(data);
//     data = JSON.parse(data);

     var CO = [];
     var SO2 = [];
     var NO2 = [];
     var O3 = [];
     var PM2_5 = [];
     var TOTAL = [];

     for(var i in data) {
       CO.push(data[i].coaqi);
       SO2.push(data[i].so2aqi);
       NO2.push(data[i].no2aqi);
       O3.push(data[i].o3aqi);
       PM2_5.push(data[i].pm25aqi);
	   TOTAL.push(data[i].totalaqi);
     }
	$("#so2aqi").html(SO2);
	$("#coaqi").html(CO);
	$("#no2aqi").html(NO2);
	$("#o3aqi").html(O3);
	$("#pm25aqi").html(PM2_5);
	$("#totalaqi").html(TOTAL);
   },
   error : function(data) {
   }
 });
 setTimeout("updateAqi()", 2000);
}
