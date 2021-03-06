@extends('master_home')

<!-- titles array -->
@section('headContent')
<title>Energy</title>
@stop

<!-- bg sources array -->
@section('bgsource')
god.jpg
@stop
@section('bodyContent') 
<script type="text/javascript"> 
setInterval("takeFromAbove()",{{C::get('game.msRefreshRate')}});

function takeFromAbove(){

  sysLE=parseInt($('#sysLE').html());
  lowerTHR=$('#lowerTHR').val();
  upperTHR=$('#upperTHR').val();
  le = parseInt($('#le').val());
  decay=parseInt($('#decay').val());
  THRpercentL = Math.round(lowerTHR/sysLE*1000*p)/p;
  THRpercentU = Math.round(upperTHR/sysLE*1000*p)/p;
  decayRate=Math.round(60*decay/le*100*p)/p;
  $('#decayRate').html(decayRate);
  $('#THRpercentL').html(THRpercentL);
  $('#THRpercentU').html(THRpercentU);
  $('#ownLE').html(le);
  lw=document.getElementById('LEwidth').style.width;
  $('#ownLEwidth').width(lw);
}

function reqRedeem(redeemLE){
  $.ajax({
    method: "POST",
    url: "{{ route('redeemLife') }}",
    data: { 'redeemLE': redeemLE },
  })
  .success(function( data ) {
    console.log("REDEEMED ",data['respLE']);
    var now=parseInt(data['stored_LE']);
    console.log("Now stored_LE ",now);

      $('#stored_LE').val(now); //update above one.
      $('#stored_LE_slide').attr("max",now);
    });
}
function redeem(){
  var redeemLE=$('#redeemLE').val();
  reqRedeem(redeemLE); 
}
reqRedeem(0);
</script>


<div class="info-box bg-red" style="opacity:0.85;width:45%;position: relative; top:3%; left:3%">
  <span class="info-box-icon"><i class="fa fa-heartbeat"></i></span>

  <div class="info-box-content">
    <span class="info-box-text">System Life Energy</span>
    <br>   
    <span id="sysLE" class="info-box-number">92,050</span>
  </div>
  <!-- /.info-box-content -->
</div> 

<!-- User LE  -->
<div class="info-box bg-aqua" style="width:45%;opacity:0.85; position: relative;top: 4%; left: 3%">
  <span class="info-box-icon"><i class="fa fa-star"></i>
    <!-- more options- --> <!-- ion ion-ios-heart-outline --> <!-- fa-star --> <!-- fa-thumbs-o-up --> <!-- fa-times-circle -->
  </span>
  <div class="info-box-content">
    <span class="info-box-text">Own Life Energy</span>
    <span id='ownLE' class="info-box-number">92,050</span>

    <!-- This width will be w=(C-L)/(U-L)*100 U=upperTHR,C=lowerTHRetc -->
    <div class="progress"> <div class="progress-bar" id="ownLEwidth" style="width: 20%"></div> </div>
    <span class="progress-description"> <i id="decayRate"> 10</i>% decrease per minute </span>

  </div>
</div>

<div class="row" style="width:46% ; position: relative; top: 5%; left: 3%; padding-left:1%;">
  <!-- Upper THR -->
  <div class=" col-md-4 info-box" style="width:50%;background:#22f474;opacity: 0.9">
    <span class="info-box-icon bg-green"><div><i class="fa fa-fw fa-users"></i></div></span>
    <div class="info-box-content">
      <span class="info-box-text"> Upper Threshold </span>
      <span class="info-box-number"> <i id="THRpercentU"> 50.1 </i><small>%</small></span>
    </div>
  </div>
  <!-- Lower THR -->
  <div class="col-md-4 info-box" style="width:50%;background:#22f474;opacity: 0.9">
    <span class="info-box-icon bg-yellow"><div><i class="fa fa-fw fa-user"></i></div></span>
    <div class="info-box-content">
      <span class="info-box-text">Lower Threshhold</span>
      <span class="info-box-number"> <i id="THRpercentL"> 5.1 </i> <small>%</small></span>
    </div><!-- /.info-box-content -->
  </div>

</div>

<!-- SCORE ! -->
<!-- <div class="small-box bg-aqua" style="width:20%;margin:2%0%0%16%;opacity:0.85">
  <div class="inner">
    <h3>150</h3> <p>profit gained</p> 
  </div>
  <div class="icon" style="color:white"> <i class="fa fa-shopping-cart"></i> </div>
</div>
-->

<div class="col-md-4" style="padding-left: 10%; position:relative; top:6%; left:3%;">
 <div class="box box-primary"  style="background:#3399ff;padding-left: 10%;padding-right:10%;padding-bottom: 5%">
  <div class="box-header">
    <h2 class="box-title" style="color: white;">Redeem Stored LE</h2>
  </div>
  <br>
  <input type='range'  class="btn btn-danger" min="0" max="{{$user->stored_LE or 1}}" id="stored_LE_slide" oninput=" $('#redeemLE').val(this.value)" value=0 /> 
  <br>
  <input type='number' id='redeemLE' value=0 /> 
  <button onclick="redeem()"  class="btn btn-danger pull-right"> REDEEM</button>
  <br>
</div>
</div>

<!-- interactive chart -->
<div class="col-md-4" style="width:45%;background:#3399ff;position: relative; top:-38%; left:18%; opacity:0.9;border-radius:10px">
  <div class="row">
    <div class="col-xs-12">
      <div class="box box-primary">
        <div class="box-header with-border" style="background:#3399ff">
          <i class="fa fa-bar-chart-o"></i>
          <h3 class="box-title">Interactive Life Energy Chart</h3>
          <div class="box-tools pull-right" style="background:#3399ff">
            
            <div class="btn-group" id="realtime_user" data-toggle="btn-toggle">
              <!-- <button type="button" class="btn btn-default btn-xs active" data-toggle="on">On</button> -->
              <!-- <button type="button" class="btn btn-default btn-xs" data-toggle="off">Off</button> -->
            </div>
          </div>
        </div>
        <!-- Here comes the chart -->
        <div id="interactive_user" style="height: 32%; padding: 0px; position: relative;"> </div>
        

      </div> <!-- /.box-body-->
    </div> <!-- /.box -->
  </div> <!-- /.col -->
</div>
<div class="progress progress-bar-vertical" style="width:15px;height:29%; position: relative; top: -31.5%; left:-25%" >
          <div id="LEheight" class="progress-bar  progress-bar-striped  progress-bar-danger"  style="height: 20%"></div>
        </div>

<div class="col-md-4" style="width:45%;background:#3399ff;position: relative;opacity:0.9; top:-36%; left:51.5% ;border-radius:10px">
  <div class="row">
    <div class="col-xs-12">
      <div class="box box-primary">
        <div class="box-header with-border" style="background:#3399ff">
          <i class="fa fa-bar-chart-o"></i>
          <h3 class="box-title">Interactive Global Life Energy Chart</h3>
          <div class="box-tools pull-right" style="background:#3399ff">
            Real time
            <div class="btn-group" id="realtime_syst" data-toggle="btn-toggle">
              <!-- <button type="button" class="btn btn-default btn-xs active" data-toggle="on">On</button> -->
              <!-- <button type="button" class="btn btn-default btn-xs" data-toggle="off">Off</button> -->
            </div>
          </div>
        </div>
        <!-- Here comes the chart -->
        <div id="interactive_syst" style="height: 32%; padding: 0px; position: relative;"> </div>
      </div> <!-- /.box-body-->
    </div> <!-- /.box -->
  </div> <!-- /.col -->
</div>


<script src="./plugins/fastclick/fastclick.js"></script>
<script src="./dist/js/app.min.js"></script>
<script src="./dist/js/demo.js"></script>
<script src="./plugins/flot/jquery.flot.min.js"></script>
<script src="./plugins/flot/jquery.flot.resize.min.js"></script>
<script src="./plugins/flot/jquery.flot.pie.min.js"></script>
<script src="./plugins/flot/jquery.flot.categories.min.js"></script>

<script>

$(function () {
    // We use an inline data source in the example, usually data would
    // be fetched from a server
    var data = [];
    var totalPoints = 100;
    for(var i=0; i<totalPoints; i++){
      data[i]=0;
    }  
    if(localStorage.getItem('data_user') !== null){
     data = JSON.parse(localStorage.getItem('data_user'));  
   }
   var cx_user=0;
   var currentTops=parseInt($('#le').val());
   if(localStorage.getItem('ctop_user')!==null){
    var ctop = JSON.parse(localStorage.getItem('ctop_user'));
    if(ctop>data[data.length-1])  currentTops = ctop;
    if(ctop<=data[data.length-1]) currentTops = data[data.length-1];
  }
// Nope - THis is what server shall send - array of previous 100 records
// Server shall send only current LE. Frontend will keep pushing the data
function getLiveLE(){
  if(data.length == 100) data.shift();
  var leval = parseInt($('#le').val());
  if(leval!=0) data.push(leval);
  var res = [];
  for (var i = 0; i < data.length; ++i) {
   if(cx_user<data[i]) cx_user = data[i];
   res.push([i, data[i]]);

 }

 return res;
}

var interactive_plot = $.plot("#interactive_user", [getLiveLE()], {
  grid: {
    borderColor: "#f3f3f3",
    borderWidth: 1,
    tickColor: "#f3f3f3"
  },
  series: {
        shadowSize: 0, // Drawing is faster without shadows
        color: "#3c8dbc"
      },
      lines: {
        fill: true, //Converts the line chart to area chart
        color: "#3c8dbc"
      },
      yaxis: {
        min: 0,
        max: currentTops,
        show: true
      },
      xaxis: {
        show: true,
      }
    });

    var updateInterval = 500; //Fetch data ever x milliseconds
    var realtime = "on"; //If == to on then fetch data every x seconds. else stop fetching
    function update() {

      interactive_plot.setData([getLiveLE()]);
      localStorage.setItem('data_user',JSON.stringify(data));
      localStorage.setItem('ctop_user',JSON.stringify(currentTops));
      if(cx_user<3*currentTops) currentTops=cx_user;
      interactive_plot.getOptions().yaxes[0].max = currentTops*1.2 ; 
      // Since the axes don't change, we don't need to call plot.setupGrid()
      interactive_plot.setupGrid();
      interactive_plot.draw();
      if (realtime === "on")
        setTimeout(update, updateInterval);
    }

    //INITIALIZE REALTIME DATA FETCHING
    if (realtime === "on") {update(); }
    //REALTIME TOGGLE
    $("#realtime_user.btn").click(function () {
      if ($(this).data("toggle") === "on") {realtime = "on"; } else {realtime = "off"; }
      update();
    });
  });


$(function () {
    // We use an inline data source in the example, usually data would
    // be fetched from a server
    var data = [], totalPoints = 100;
    for(var i=0; i<totalPoints; i++){
      data[i]=0;
    }

    if(localStorage.getItem('data_syst') !== null){
      data = JSON.parse(localStorage.getItem('data_syst'));  
    }
    //check if localstorage has data array or not if then use that
    var currentTop=parseInt($('#sysLE').html());
    var cx_user=0;

    if(localStorage.getItem('ctop_syst')!==null){
      var ctop = JSON.parse(localStorage.getItem('ctop_syst'));
      if(ctop>data[data.length-1])  currentTop = ctop;
      if(ctop<=data[data.length-1]) currentTop = data[data.length-1];
    }
    //this is for getting the maximums and minimums

// Nope - THis is what server shall send - array of previous 100 records
// Server shall send only current LE. Frontend will keep pushing the data
function getLiveLE(){
  if(data.length==100) data.shift();
  var gleval = parseInt($('#sysLE').html());
  if(gleval>=5000)data.push(gleval);
  var res = [];
  for (var i = 0; i < data.length; ++i) {
   if(cx_user<data[i]) cx_user = data[i];
   res.push([i, data[i]]);
   
 }
 
 return res;
}

var interactive_plot = $.plot("#interactive_syst", [getLiveLE()], {
  grid: {
    borderColor: "#f3f3f3",
    borderWidth: 1,
    tickColor: "#f3f3f3"
  },
  series: {
        shadowSize: 0, // Drawing is faster without shadows
        color: "#cd554a"
      },
      lines: {
        fill: true, //Converts the line chart to area chart
        color: "#cd554a"
      },
      yaxis: {
        min: 0,
        max: currentTop,
        show: true
      },
      xaxis: {
        show: true
      }
    });

    var updateInterval = 500; //Fetch data ever x milliseconds
    var realtime = "on"; //If == to on then fetch data every x seconds. else stop fetching
    function update() {
      $('#LEheight').height($('#LEwidth').width());
      
      var h = Math.round( 
        $('#LEheight').height() / $('#LEheight').parent().height() * 100 
        );
      $('#LEheight').attr('class','progress-bar  progress-bar-striped  progress-bar-'+((h>50)?'success':'danger'));
      interactive_plot.setData([getLiveLE()]);
      localStorage.setItem('data_syst',JSON.stringify(data));
      localStorage.setItem('ctop_syst',JSON.stringify(currentTop));
      if(cx_user<3*currentTop) currentTop=cx_user;

      interactive_plot.getOptions().yaxes[0].max = currentTop*1.2 ; 
      // Since the axes don't change, we don't need to call plot.setupGrid()
      interactive_plot.setupGrid();
      interactive_plot.draw();
      if (realtime === "on")
        setTimeout(update, updateInterval);
    }

    //INITIALIZE REALTIME DATA FETCHING
    if (realtime === "on") {update(); }
    //REALTIME TOGGLE
    $("#realtime_syst.btn").click(function () {
      if ($(this).data("toggle") === "on") {realtime = "on"; } else {realtime = "off"; }
      update();
    });
  });


</script>
@stop