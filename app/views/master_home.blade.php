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
    header input {
      width: 100px;
    }
    .headLink{
      color:red;
      font-size: 15px;
    }
  </style>
</head>

<body background="@yield('bgsource')" style="background-repeat:no-repeat;background-size: cover;" >
  <script type="text/javascript">
  var p =Math.pow(10,3); //precision

  var messages=[
  'Clean',
  'Warning Down',
  'Level Down',
  'Level Up',
  'Some one found to <b> switch </b>',
  'F yeah !',
  ];


//merge these two MUCH MUCH LATER
setInterval(function(){
  decayHandle();
  thresholdHandle();
},1000);

function decayHandle(){
  $.ajax({
    method: "POST",
    url: "{{ route('decayHandle') }}",
  // data: { 'name': "Johnny", 'location': "Boston" },
  success: function( data ) {
    var le=parseInt(data['le']);
    var decay=parseInt(data['decay']);
    $('#le').val(le);
    
    $('#decay').val(decay);

    //these val() can be moved into thresholdHandle()
    lowerTHR=$('#lowerTHR').val();
    upperTHR=$('#upperTHR').val();
    
    var eta=(le-lowerTHR)/decay;
    $('#ETA').val(Math.round(eta*p)/p);

    LEwidth=(le-lowerTHR)/(upperTHR-lowerTHR)*100+'%';
    $('#LEwidth').width(LEwidth);
    
  },
  
  error: function(){// Server Disconnected
    alert('Cannot connect to the server'); 
  }

});
}

function thresholdHandle(){

 $.ajax({
  method: "POST",
  url: "{{ route('thresholdHandle2') }}",
  // data: { 'name': "Johnny", 'location': "Boston" },
})
 .success(function( data ) {
  $('#sysLE').html(parseInt(data['sysLE']));
  $('#upperTHR').val(parseInt(data['upperTHR']));
  $('#lowerTHR').val(parseInt(data['lowerTHR']));
//<!-- We also have $user to be used -->
$('#active_cat').html(data['active_cat'].toString());

m=data['msg'];
//later use push to show more messages
// notifs.push(messages[m]);
$('#msg').html(messages[m]);
});

}

function insertTable(cellData,divID,black){

  var start=0;
  var tableContainer=document.getElementById(divID);
  var table1= document.createElement('table');
  table1.className="table table-bordered";
  var tableHeight=cellData.length-start,tableWidth=cellData[start+1].length;

  for(var i=start;i<tableHeight;i++){
    var current_row=table1.insertRow();
    if(i==start && black){
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

<header class="main-header" style="background:#3399ff;opacity:0.85">
  <span class="info-box-icon bg-aqua" style="opacity:0.85"> <i class="fa fa-fw fa-user"><div id="active_cat" align="center" style="font-size: 40%"></div></i></span>
  <nav class="navbar navbar-static-top">
    <div class="row">


      <div class="col-md-3">
        <div class="row">

          <ul class="nav navbar-nav "> 
            <!-- We also have $user to be used -->
            <!-- common one- -->
            <li> <a class="headLink"  href="{{ URL::route('energy') }}">Energy</a> </li>
            
            <?php $user=Auth::user()->get();$catLinks = C::get('master.catLinks'); ?>
            @foreach($catLinks[$user->category] as $linkName=>$title)
            <li> <a class="headLink"  href="{{ URL::route($linkName) }}">{{$title}}</a> </li>
            @endforeach
          </ul>

        </div>
      </div>
      <div class="col-md-2"> 
        Notification :
        <ul> <li id="msg"> </li> </ul>
      </div>
      <div class="col-md-6">
        <p> 
          Lower  <input  readonly="readonly" disabled id='lowerTHR' value=0 /> 
          &emsp;&emsp;&emsp;
          User le        <input readonly="readonly" disabled id='le' value=0 >&emsp;
          decay <input readonly="readonly" disabled id='decay' value=0 /> 
          ETA  <input readonly="readonly" disabled  id='ETA' value=0 />&emsp;

          <i class="nav navbar-right">
            Upper  <input readonly="readonly" disabled id='upperTHR' value=0 /> 
          </i>
        </p>
        <div class="progress" style="height:10px;" >
          <div id="LEwidth" class="progress-bar" style="width: 20%"></div>
        </div>

        <div class="row">
          Stored_LE <input readonly="readonly" disabled id='stored_LE' value=0 /> 
        </div>
      </div>

    </div>

  </nav>
</header>

@yield('bodyContent')

</body>
