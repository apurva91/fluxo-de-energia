<html>
<!-- Plugins contains flot, jQuery, fastclick -->
<head>
  <meta charset="utf-8"> <meta http-equiv="X-UA-Compatible" content="IE=edge">  
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- font awesome i.e. fa has icons -->
  <!-- The style sheets depend on the contents in their folders -->
  <script src="{{ URL::asset('./plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
  <script src="{{ URL::asset('./bootstrap/js/bootstrap.min.js') }}"></script>
  <link rel="stylesheet" href="{{ URL::asset('font-awesome-4.3.0/css/font-awesome.min.css') }}" />
  <link rel="stylesheet" href="{{ URL::asset('ionicons-2.0.1/css/ionicons.min.css') }}" />
  <link rel="stylesheet" href="{{ URL::asset('dist/css/AdminLTE.min.css') }}" />
  <link rel="stylesheet" href="{{ URL::asset('dist/css/skins/_all-skins.min.css') }}" />
  <link rel="stylesheet" href="{{ URL::asset('bootstrap/css/bootstrap.min.css') }}" />

  @yield('headContent')
  
  <style type="text/css">
  .progress-bar-vertical {
    width: 20px;
    min-height: 100px;
    display: flex;
    align-items: flex-end;
    margin-right: 20px;
    float: left;
  }

  .progress-bar-vertical .progress-bar {
    width: 100%;
    height: 0;
    -webkit-transition: height 0.6s ease;
    -o-transition: height 0.6s ease;
    transition: height 0.6s ease;
  }

  </style>
</head>

<body background="@yield('bgsource')" style="background-repeat:no-repeat;background-size: cover;" >
  <script type="text/javascript">
  var upperG = 0;
  var upperI = 0;
  var upperF = 0;
  var lowerF = 0;
  var lowerI = 0;
  var lowerG = 0;
  var p =Math.pow(10,3); //(x*p)/p precision of all calculations here
  
  var messages={{ json_encode(C::get('game.notifHTMLs')); }}

//merge these two MUCH MUCH LATER
setInterval(function(){
  decayHandle();
  thresholdHandle();
},{{C::get('game.msRefreshRate')}});

function reloadPage(){
  window.location.href += "";
}

function decayHandle(){
  $.ajax({
    method: "POST",
    url: "{{ route('decayHandle') }}",
    success: function( data ) {
      var le=parseInt(data['le']);
      var decay=parseInt(data['decay']);
      $('#le').val(le);

      $('#decay').val(decay);

    //these val() can be moved into thresholdHandle()
    lowerTHR=$('#lowerTHR').val();
    upperTHR=$('#upperTHR').val();
    var eta=(le-lowerTHR)/decay;
    $('#ETA').val(Math.round(eta/60*p)/p + ' m');
    $('#stored_LE').val(data['stored_LE']);
  // console.log
  LEwidth=(le-lowerTHR)/(upperTHR-lowerTHR)*100+'%';
  $('#LEwidth').width(LEwidth);

  if(data['reload']=='1')
    reloadPage();
},

  error: function(){// Server Disconnected
    //alert('Cannot connect to the server'); 
  }

});
}
function thresholdHandle(){
 $.ajax({
  method: "POST",
  url: "{{ route('thresholdHandle') }}",
  // data: { 'name': "Johnny", 'location': "Boston" },
})
 .success(function( data ) {

  $('#sysLE').html(parseInt(data['sysLE']));
  $('#upperTHR').val(parseInt(data['upperTHR']));
  $('#lowerTHR').val(parseInt(data['lowerTHR']));
//<!-- We also have $user to be used -->
// console.log(data);
upperG  = parseInt(data['upperG']);     
upperI  = parseInt(data['upperI']);     
upperF  = parseInt(data['upperF']);     
lowerF  = parseInt(data['lowerF']);     
lowerI  = parseInt(data['lowerI']);     
lowerG  = parseInt(data['lowerG']);     
$('#active_cat').html(data['active_cat'].toString());

m=data['msg'];
//later use push to show more messages
// notifs.push(messages[m]);
$('#msg').html(messages[m]);

if(data['reload']=='1')
  reloadPage();
});

}

function insertTable(cellData,divID,black){

  var offset=0;
  var tableContainer=document.getElementById(divID);
  var table1= document.createElement('table');
  table1.className="table table-bordered";
  var tableHeight=cellData.length-offset,tableWidth=cellData[offset].length;

  for(var i=offset;i<tableHeight;i++){
    var current_row=table1.insertRow();
    if(i==offset && black){
      current_row.style="background:black;color:white";
    }
    for(var j=0; j<tableWidth;j++){
      var current_col=current_row.insertCell();
      current_col.innerHTML='<strong>'+cellData[i%tableHeight][j%tableWidth]+'</strong>';
    }
  }
  tableContainer.appendChild(table1);
}

</script>
<!--   ----------------------------------- -->
<script type="text/javascript"> 
function newsGetNow(){
  $.ajax({
    url:'{{asset("news.txt")}}',
    dataType:'text',
    success: function(data) {
      $('#news_panel').html(data);
      $('#news_panel').scrollTop($('#news_panel')[0].scrollHeight);
    }
  });
};

</script>

<!--   ----------------------------------- -->

<header class="main-header" style="background:#3399ff;opacity:0.85; position:relative; height: 14%">

  <span class="info-box-icon bg-aqua" style="opacity:0.85; position:relative; height: 100%">
    <i class="fa fa-fw fa-user" style="position:relative; top:10%">
      <div id="active_cat" align="center" style="font-size: 40%"></div></i>
    </span>
    <!-- <nav class="navbar navbar-static-top"> -->
    <div>
      <div class="row">
        <div class="col-xs-4" style="position: relative; left:3%">

          <div class="row">
            <br>
          </div>
          <div class="row">
            <!-- <ul class="nav navbar-nav ">  -->
            <!-- We also have $user to be used -->
            <!-- common one- -->
            <div class="btn-group btn-group-justified">
              <a href="{{ URL::route('energy') }}" class="btn btn-primary" role="button">Energy</a>
              <a href="{{ URL::route('leaderBoard') }}" class="btn btn-primary" role="button"> Leader Board</a>
              <?php $user=Auth::user()->get();$catLinks = C::get('master.catLinks'); ?>
              @foreach($catLinks[$user->category] as $linkName=>$title)
              <a href="{{ URL::route($linkName) }}" class="btn btn-primary" role="button">{{$title}}</a>
              @endforeach
            </div>
            <!-- </ul> -->

          </div>
          <div class="row">
            <br>
          </div>
          <div class="row">
            <div class="progress" style="height:10px;" >
              <div id="LEwidth" class="progress-bar" style="width: 20%"></div>
            </div>
          </div>
        </div>

        <div class="col-xs-6" style="position: relative; left:5%">

          <div class="gap"><br></div>
          <!-- <div class="input-group"> -->
          <table >
            <tr>
              <td>
                <button class="btn btn-sm btn-block bg-purple ">Lower</button>
              </td>
              <td>
                <input  readonly="readonly" class="btn btn-block  btn-sm" disabled id='lowerTHR' value=0 /> 
              </td>
              <td>
                <button class="btn btn-sm btn-block bg-purple ">User le</button>
              </td>
              <td>
                <input readonly="readonly"  class="btn  btn-block btn-sm" disabled id='le' value=0 >
              </td>
              <td>
                <button class="btn btn-sm btn-block bg-purple ">Upper</button>
              </td>
              <td>
                <input readonly="readonly"  class="btn btn-block btn-sm" disabled id='upperTHR' value=0 /> 
              </td>
            </tr>
            <tr>
              <td>
                <button class="btn btn-sm btn-block bg-purple ">decay</button>
              </td>
              <td>
                <input readonly="readonly"  class="btn btn-block btn-sm" disabled id='decay' value=0 /> 
              </td>
              <td>

                <button class="btn btn-sm btn-block bg-purple ">ETA</button>
              </td>
              <td>
                <input readonly="readonly"  class="btn  btn-block btn-sm" disabled  id='ETA' value=0 />
              </td>
              <td>
                <button class="btn btn-sm btn-block bg-purple ">Stored LE</button>
              </td>
              <td>
                <input readonly="readonly"  class="btn btn-block btn-sm" disabled id='stored_LE' value=0 /> 
              </td>
            </tr>
          </table>
        </div>

        <div class="col-xs-1" style="width:10% ; position: relative;"> 
          <div class="row"><br></div>
          <table>
            <tr>
              <td>
                <label> <small>Status:</small> <span id="msg"> </span> </label>
              </td>
            </tr>
            <tr>
              <td>
                <div onclick="openNav()" id="news_btn"><button class="btn-block btn-success btn-sm">News &#9776;</button>
                </div>
              </td>
            </tr>
          </table>
          
        </div>


      </div>
    </div>

    <!-- </nav> -->
  </header>


  <script>
  function openNav() {
    if(document.getElementById("news_pan").style.display == "block")
      document.getElementById("news_pan").style.display = "none";
    else {newsGetNow();
      document.getElementById("news_pan").style.display = "block";

      document.getElementById("news_btn").style.color = "white";
    }
  }

  function closeNav() {
    document.getElementById("news_pan").style.display = "none";
  }
  </script>

  <div id="news_pan" class="container" style="background-color: white; padding:10px; position: fixed; right:0px; height:88%; z-index: 99; display: none;width:400px; font-family: 'Ubuntu';color:#666666; ">

    <h1 align="center" style="margin-top: 5%;"> News</h1>
    <a href="javascript:void(0)" class="closebtn" style="position: absolute;
    top: 0;
    right: 25px;
    color: #666666;
    text-decoration: none;
    font-size: 60px;
    margin-left: 50px;" onclick="closeNav()">&times;</a>
    <div id="news_panel"  style="line-height: 20px;font-size: 15px; overflow-y: auto; height: 83%;">
    </div>
  </div>
  @yield('bodyContent')

</body>
</html>