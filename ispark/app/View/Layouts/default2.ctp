<!DOCTYPE html>
<html>
<style>
body, html {
  height: 100%;
  margin: 0;
}

.bgimg {

    background-image: url('https://isparkindia.in/wp-content/uploads/2018/09/slider-3.jpg');
    
  height: 100%;
  background-position: center;
  background-size: cover;
  position: relative;
  color: white;
  font-family: "Courier New", Courier, monospace;
  font-size: 25px;
}

.topleft {
  position: absolute;
  top: 0;
  left: 16px;
}

.bottomleft {
  position: absolute;
  bottom: 0;
  left: 16px;
color:#FF4500;
}

.middle {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
    color:#FF4500;
}

hr {
  margin: auto;
  width: 40%;
}
</style>

<script>
function startTime() {
  var today = new Date();
  var h = today.getHours();
  var m = today.getMinutes();
  var s = today.getSeconds();
  m = checkTime(m);
  s = checkTime(s);
  document.getElementById('txt').innerHTML =
  h + ":" + m + ":" + s;
  var t = setTimeout(startTime, 500);
}
function checkTime(i) {
  if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
  return i;
}
</script>
<body onload="startTime()" >
    <div class="bgimg">
        <div class="topleft">
            <p><img src="https://isparkindia.in/wp-content/uploads/2018/09/logo.png"></p>
        </div>
        
        <div class="middle">
            <h1>COMING SOON</h1>
            <div id="txt"></div>
            <p>Website is Under Construction</p>
        </div>
        
        <div class="bottomleft">
            <p>Please contact to admin</p>
        </div>
    </div>
</body>
</html>
