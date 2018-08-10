$(document).ready(function(){
	updateRaw();
});

function updateRaw () {
 $.ajax({
   url : "/sensor/real/view",
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
     var temp = [];
	 var heart = [];

     for(var i in data) {
       CO.push(data[i].co);
       SO2.push(data[i].so2);
       NO2.push(data[i].no2);
       O3.push(data[i].o3);
       PM2_5.push(data[i].pm25);
       temp.push(data[i].temp);
	   heart.push(data[i].heart);
     }
	$("#so2").html(SO2);
	$("#co").html(CO);
	$("#no2").html(NO2);
	$("#o3").html(O3);
	$("#pm25").html(PM2_5);
	$("#temp").html(temp);
	$("#heart").html(heart);
   },
   error : function(data) {
   }
  });
  setTimeout("updateRaw()", 2000);
}
