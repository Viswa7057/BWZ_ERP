/* global Chart:false */

$(function () {
  'use strict'

  var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
  }

  var mode = 'index'
  var intersect = true
  
  
function monthlyCallDetails(){
  var $salesChart = $('#sales-chart')
  // eslint-disable-next-line no-unused-vars
  const xhttp = new XMLHttpRequest();
		  xhttp.onload = function() {	
			var inarrData = Array(); 	
			var outarrData = Array();
			var result1 = this.responseText;	
		
			var inoutData = result1.split("**");
			var result = inoutData[0];
			var resultArray = result.split(",");		
			for(var i=0;i<resultArray.length;i++){
				inarrData.push(parseInt(resultArray[i]));			
			}
			
			var outData = inoutData[1];
			var outDataArray = outData.split(",");		
			for(var i=0;i<outDataArray.length;i++){
				outarrData.push(parseInt(outDataArray[i]));			
			}
  var salesChart = new Chart($salesChart, {
    type: 'bar',
    data: {
      labels: ['Jan','Feb','Mar','Apr','May','June','July','Aug','Sept','Oct','Nov','Dec'], 
      datasets: [
        {
          backgroundColor: '#007bff',
          borderColor: '#007bff',
          data: inarrData
        },
        {
          backgroundColor: '#ced4da',
          borderColor: '#ced4da',
          data: outarrData
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value) {
              if (value >= 100) {
                value /= 100
                value += 'k'
              }

              return '' + value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })
  }
  xhttp.open("GET", "ajax/hourlyCallDetails.php?monthlyCallDetails=monthly Call Details", true);
		  xhttp.send();
}
monthlyCallDetails();
  const interval = setInterval(function() {monthlyCallDetails()  }, 5000);   
  
  
		function hourlyCallDetails(){
 var $visitorsChart = $('#visitors-chart')  //hourlyCallDetails.php
  // eslint-disable-next-line no-unused-vars
    
		const xhttp = new XMLHttpRequest();
		  xhttp.onload = function() {	
			var inarrData = Array(); 	
			var outarrData = Array();
			var result1 = this.responseText;	
			var inoutData = result1.split("**");
			var result = inoutData[0];
			var resultArray = result.split(",");		
			for(var i=0;i<resultArray.length;i++){
				inarrData.push(parseInt(resultArray[i]));			
			}
			
			var outData = inoutData[1];
			var outDataArray = outData.split(",");		
			for(var i=0;i<outDataArray.length;i++){
				outarrData.push(parseInt(outDataArray[i]));			
			}

  var visitorsChart = new Chart($visitorsChart, {	
    type: 'bar',
    data: {
      labels: ['00-01', '01-02', '02-03', '03-04', '04-05', '05-06', '06-07', '07-08', '08-09', '09-10', '10-11', '11-12', '12-13', '13-14', '14-15', '15-16', '16-17', '17-18', '18-19', '19-20', '20-21', '21-22', '22-23'],
      datasets: [
        {
          backgroundColor: '#007bff',
          borderColor: '#007bff',
          data: inarrData
        },
        {
          backgroundColor: '#ced4da',
          borderColor: '#ced4da',
          data: outarrData
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value) {
              if (value >= 100) {
                value /= 100
                value += 'k'
              }

              return '' + value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })

			}
		  xhttp.open("GET", "ajax/hourlyCallDetails.php?hourlyCallDetails=hourly Call Details", true);
		  xhttp.send();
}
		  hourlyCallDetails();
  const interval1 = setInterval(function() {hourlyCallDetails()  }, 5000); 
  
  
  function agentsReview(){

    const xhttp = new XMLHttpRequest();
	  xhttp.onload = function() {    
			var agentInfo = this.responseText;
			var agentInfoSplit = agentInfo.split("**");
		//document.getElementById("Agents_INCALL").innerHTML =agentInfoSplit[0] ;
		document.getElementById("Agents_PAUSE").innerHTML =agentInfoSplit[1] ;
		document.getElementById("Agents_DISPO").innerHTML =agentInfoSplit[2] ;
		document.getElementById("Agents_READY").innerHTML =agentInfoSplit[3] ;
		}
	  xhttp.open("GET","ajax/hourlyCallDetails.php?agentsReview=agents Review", true);
	  xhttp.send();
	  
  }
  agentsReview()
  const interval12 = setInterval(function() {agentsReview()  }, 5000);
  
  
    function agentsCalls(){
    const xhttp = new XMLHttpRequest();
	  xhttp.onload = function() {  		
		document.getElementById("agents_Calls").innerHTML =this.responseText;	
		}
	  xhttp.open("GET","ajax/hourlyCallDetails.php?agentsCalls=agents Calls", true);
	  xhttp.send();
	  
  }
  agentsCalls()
  //const interval123 = setInterval(function() {agentsCalls()  }, 5000); 
  
  
  
 /* var $visitorsChart = $('#visitors-chart')
  // eslint-disable-next-line no-unused-vars
  var visitorsChart = new Chart($visitorsChart, {
    data: {
      labels: ['18th', '20th', '22nd', '24th', '26th', '28th', '30th'],
      datasets: [{
        type: 'line',
        data: [100, 120, 170, 167, 180, 177, 200],
        backgroundColor: 'transparent',
        borderColor: '#007bff',
        pointBorderColor: '#007bff',
        pointBackgroundColor: '#007bff',
        fill: false
        // pointHoverBackgroundColor: '#007bff',
        // pointHoverBorderColor    : '#007bff'
      },
      {
        type: 'line',
        data: [60, 80, 70, 67, 80, 77, 100],
        backgroundColor: 'tansparent',
        borderColor: '#ced4da',
        pointBorderColor: '#ced4da',
        pointBackgroundColor: '#ced4da',
        fill: false
        // pointHoverBackgroundColor: '#ced4da',
        // pointHoverBorderColor    : '#ced4da'
      }]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,
            suggestedMax: 200
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  }) */
})

// lgtm [js/unused-local-variable]
