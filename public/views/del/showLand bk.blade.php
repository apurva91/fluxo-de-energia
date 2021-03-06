@extends('master_home')

@section('bodyContent')
<br>
<script>

  var c1={{ $c1 }};
  var c2={{ $c2 }};
  var c3={{ $c3 }};
  var c4={{ $c4 }};
  var bp={{ $fruitBP }};

  function getUC(){

    var ET=document.getElementById('ET').value;
    var GT=1;
    var Tol=document.getElementById('Tol').value;
    var quality=document.getElementById('quality_factor').value;
    return bp*(c1*quality+c2*GT+c3*ET)*(1+c4*Tol);
  }


  function update(){
    var u=document.getElementById('unit_price');
    u.value=getUC();
    console.log(u.value);
  }

  setInterval("update()",1000);

  function showGT(id){
     $("#status").html('Modal: '+'you have fert on '+id+'!');
//TODO 
///SEND AJAX HERE to update fruit data & number & alter land (with backend check)

}
function getRGT(id){
  //TODO
  //Ajax to update remaining GT - onclick Growing land
  var RGT=7;
  $("#status").html(id+' : will grow a fruit in '+RGT+' minutes !');
}

function fruitTable(){
  var table1=document.getElementById("fruitTable");

  var tableHeight=4,tableWidth=4;

  for(var i=0;i<tableHeight;i++){
    var current_row=table1.insertRow();
    for(var j=0; j<tableWidth;j++){
      var current_col=current_row.insertCell();
      current_col.innerHTML="fruit detail"+j;
    }
  }

}

var state1='Planted ',state2='Fert &nbsp &nbsp';
function makeBox(state,id){
  var Icon='<span style="font-size:30px;">&nbsp '+(state==1?state1:(state==2?state2:'Unused'))+'&nbsp</span>';
  var block= '<label><input type="checkBox" name="land_ids[]" onclick="getRGT(this.id)" id="land'+id+'" value="'+id+'"/>'+Icon+'</label>';
  if(state==2 || state==0)block='<label><button type="button"'+(state==0?'':'onclick="showGT(this.id)"')+' id="land'+id+'"/>'+Icon+'</label>';
  return block;
}

function makeLand(divID,u,f,t){

  var cellCount=0;
  var state=0;
  var tableContainer=document.getElementById(divID);
  var table1= document.createElement('table');
    table1.cellSpacing="40";//check class
    var tableHeight=t/4+1,tableWidth=4;
    for(var i=0;i<tableHeight && cellCount<t;i++){
      var current_row=table1.insertRow();
      for(var j=0; j<tableWidth;j++){
        var current_col=current_row.insertCell();
        if(cellCount<f){current_col.style="background:orange;color:white";state=2;}
        else if(cellCount>=f && cellCount<(f+u)){current_col.style="background: lightgreen;color:white";state=1;}
        else{current_col.style="background:grey ;color:white";state=0;}
        
        current_col.innerHTML=makeBox(state,cellCount+1);
        
        cellCount++;7
      }
    }
    tableContainer.appendChild(table1);
  }

</script>
<table border="1px" cellpadding="20px">

  <tr>
    <td>
      {{ Form::open(array('url' => route("plantFert"))) }}
      <div id="Land">  </div>
    </td>
    <td>
      <div id="status">  </div>

      <pre>
        <label>CHOOSE FERT :   <select name='purchase_id' id='purchase_id'>
          @foreach ($purchases as $purch)
          @if($purch->product->category=="fertilizer")
          <option value="{{ $purch->id }}">{{ $purch->product->god->user->username or 'GOD'}}'s  {{ $purch->product->category }} {{ $purch->product->name }} ({{ $purch->id }})
          </option>
          @endif
          @endforeach
        </select>
      </label>
      <label style="position: relative;left:40%;top: 100%"><input type="submit" value="Apply"/></label><br>
      {{ Form::close() }}
    </pre>
  </td>
</tr>

<tr>
  <td>
    <pre>
      {{ Form::open(array('url' => route("plantSeed"))) }}
      CHOOSE SEED TO DEPLOY : 
      <label><select name='purchase_id' id='purchase_id'>
        @foreach ($purchases as $purch)
        @if($purch->product->category=="seed")
        <option value="{{ $purch->id }}">{{ $purch->product->god->user->username or 'GOD'}}'s  {{ $purch->product->category }} {{ $purch->product->name }} ({{ $purch->id }})
        </option>
        @endif
        @endforeach
      </select>
    </label>
    ENTER FRUIT DETAILS, All Fields below are required : 
    <label>name: <input type='text' name='name' id='name' value="myFruit" /></label><br>
    <label>description: <input type='text' name='description' id='description' value="description" /></label><br>
    <label>quality_factor : <input min="5" max="100" value="10" type="range" name='quality_factor' id='quality_factor'/></label><br>
    <label>ET : <input min="5" max="30" value="5" type="range" name='ET' id='ET'/></label><br>
    <label>Tolerance : <input min="0" max="100" type="range" name='Tol' id='Tol' value=4/></label><br>
    <label>unit_price: <input readonly="readonly" type='number' id='unit_price' name='unit_price' value="2000"/></label><br>
    <!-- Disabled ones are not sent -->
    <label style="position: relative;left:40%;top: 100%"><input type="submit" value="Deploy"/></label><br>
    {{ Form::close() }}
  </pre>
</td>
   <td>
      {{ Form::open(array('url' => route("plantLand"))) }}
      <pre>
        <label>CHOOSE LAND :   <select name='purchase_id' id='purchase_id'>
          @foreach ($purchases as $purch)
          @if($purch->product->category=="land")
          <option value="{{ $purch->id }}">{{ $purch->product->god->user->username or 'GOD'}}'s  {{ $purch->product->category }} {{ $purch->product->name }} ({{ $purch->id }})
          </option>
          @endif
          @endforeach
        </select>
      </label>
      <label style="position: relative;left:40%;top: 100%"><input type="submit" value="Add"/></label><br>
      {{ Form::close() }}
    </pre>
  </td>
</tr>
</table>






<table border="1px" id="fruitTable">
  <tr><td>
    FRUITS HERE
  </td></tr>

  @foreach ($fruits as $fruit)
  <tr><td>
    {{$fruit->id}}
  </td></tr>
  @endforeach
</table>

<script>
  fruitTable();
  makeLand("Land",{{$used_land}},{{$fert_land}},{{$total_land}});
</script>
<br>
@endsection
