@extends('layouts.header')
<!-- <h2>a-small-miracle</h2>
<audio controls id="audio">
  <source src="{{ asset('music/a-small-miracle-132333.mp3') }}" type="audio/mpeg">
    Your browser does not support the audio element.
</audio> -->
<!-- <h2>awaken</h2>
<audio controls id="audio">
  <source src="{{ asset('music/awaken-136824.mp3') }}" type="audio/mpeg">
    Your browser does not support the audio element.
</audio>
<h2>easy-lifestyle</h2>
<audio controls id="audio">
  <source src="{{ asset('music/easy-lifestyle-137766.mp3') }}" type="audio/mpeg">
    Your browser does not support the audio element.
</audio>
<h2>relaxed</h2>
<audio controls id="audio">
  <source src="{{ asset('music/relaxed-vlog-night-street-131746.mp3') }}" type="audio/mpeg">
    Your browser does not support the audio element.
</audio>
<h2>waterfall</h2>
<audio controls id="audio">
  <source src="{{ asset('music/waterfall-140894.mp3') }}" type="audio/mpeg">
    Your browser does not support the audio element.
</audio> -->

<div class="main">
    <p id="logo"><i class="fa fa-music"></i>Music</p>
    <!--- left part --->
    <div class="left">
    <!--- song img --->
    <img id="track_image">
    <div class="volume">
        <p id="volume_show">90</p>
        <i class="fa fa-volume-up" aria-hidden="true" onclick="mute_sound()" id="volume_icon"></i>
        <input type="range" min="0" max="100" value="90" onchange="volume_change()" id="volume">  
    </div>
    </div>
    <!--- right part --->
    <div class="right">
    <div class="show_song_no">
        <p id="present">1</p>
        <p>/</p>
        <p id="total">5</p>
    </div>
    <!--- song title & artist name --->
    <p id="title">title.mp3</p>
    <p id="artist">Artist name</p>
    <!--- middle part --->
    <div class="middle">
        <button onclick="previous_song()" id="pre"><i class="fa fa-step-backward" aria-hidden="true"></i></button>
        <button onclick="justplay()" id="play"><i class="fa fa-play" aria-hidden="true"></i></button>
        <button onclick="next_song()" id="next"><i class="fa fa-step-forward" aria-hidden="true"></i></button>
    </div>
    <!--- song duration part --->
    <div class="duration">
        <input type="range" min="0" max="100" value="0" id="duration_slider" onchange="change_duration()">
    </div>
    <button id="auto" onclick="autoplay_switch()">Auto play <i class="fa fa-circle-o-notch" aria-hidden="true"></i></button>
    </div>
</div>

<style type="text/css">
    * {
  margin: 0;
  padding: 0;
  font-family: Arial, Helvetica, sans-serif;
}
body {
  height: 100vh;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}
.main {
  position: relative;
  height: 80%;
  width: 80%;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(to right, #5d6d7e, #566573);
}
.main button {
  padding: 10px 12px;
  margin: 0 10px;
}
.main #logo {
  position: absolute;
  top: 10px;
  left: 30px;
  font-size: 25px;
  color: #ccc;
}
.main #logo i {
  margin-right: 15px;
}

/* left & right part */
.right,
.left {
  position: relative;
  height: 100%;
  width: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
}

/* song image */
.left img {
  height: 300px;
  width: 80%;
  border-radius: 15px;
  box-shadow: 1px 0px 20px 12px rgba(240, 240, 240, 0.2);
}

/* both range slider part */
input[type="range"] {
  -webkit-appearance: none;
  width: 50%;
  outline: none;
  height: 2px;
  margin: 0 15px;
}
input[type="range"]::-webkit-slider-thumb {
  -webkit-appearance: none;
  height: 20px;
  width: 20px;
  background: #ff8a65;
  border-radius: 50%;
  cursor: pointer;
}
.right input[type="range"] {
  width: 40%;
}

/* volume part */
.left .volume {
  position: absolute;
  bottom: 10%;
  left: 0;
  width: 100%;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
}
.left .volume p {
  font-size: 15px;
}
.left .volume i {
  cursor: pointer;
  padding: 8px 12px;
  background: #ff8a65;
}
.left .volume i:hover {
  background: rgba(245, 245, 245, 0.1);
}
.volume #volume_show {
  padding: 8px 12px;
  margin: 0 5px 0 0;
  background: rgba(245, 245, 245, 0.1);
}

/* right part */
.right .middle {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}
.right .middle button {
  border: none;
  height: 70px;
  width: 70px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  outline: none;
  transition: 0.5s;
  background: rgba(255, 255, 255, 0.1);
}
.right #title {
  position: absolute;
  top: 60px;
  left: 50%;
  transform: translateX(-50%);
  text-transform: capitalize;
  color: #fff;
  font-size: 35px;
}
.right #artist {
  position: absolute;
  top: 110px;
  left: 50%;
  transform: translateX(-50%);
  text-transform: capitalize;
  color: #fff;
  font-size: 18px;
}
.right .duration {
  position: absolute;
  bottom: 20%;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 20px;
  margin-top: 40px;
}
.right .duration p {
  color: #fff;
  font-size: 15px;
  margin-left: 20px;
}
.right #auto {
  font-size: 18px;
  cursor: pointer;
  margin-top: 45px;
  border: none;
  padding: 10px 14px;
  color: #fff;
  background: rgba(255, 255, 255, 0.2);
  outline: none;
  border-radius: 10px;
}
.right #auto i {
  margin-left: 8px;
}
#play {
  background: #ff8a65;
}
.right button:hover {
  background: #ff8a65;
}
.right i:before {
  color: #fff;
  font-size: 20px;
}

.right .show_song_no {
  position: absolute;
  top: 10px;
  right: 10px;
  width: 30px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 8px 12px;
  color: #fff;
  border-radius: 5px;
  background: rgba(255, 255, 255, 0.2);
}
.right .show_song_no p:nth-child(2) {
  margin: 0 5px;
}

</style>
@extends('layouts.footer')