
var speed=50; // lower number for faster
var flakes=20; // number of flakes
var flake_image="/images/neve.png"; // location of snowflake graphic

/***************************\
*Winter Drifting Snow Effect*
*(c) 2006   mf2fm web-design*
*  http://www.mf2fm.com/rv  *
* DON'T EDIT BELOW THIS BOX *
\***************************/
/* prelevato ed illustrato su Web-Link.it 
******************************************/
var swide, shigh;
var dx=new Array();
var xp=new Array();
var yp=new Array();
var am=new Array();
var sty=new Array();

window.onload=function() { if (document.getElementById) {
  var k, f, b;
  b=document.createElement("div");
  b.style.position="absolute";
  b.style.zIndex = "1";
  b.setAttribute("id", "bod");
  document.body.appendChild(b);
  set_scroll();
  set_width();
  for (var i=0; i<flakes; i++) {
    dx[i]=0;
    am[i]=Math.random()*20;
    xp[i]=am[i]+Math.random()*(swide-2*am[i]-25);
    yp[i]=Math.random()*shigh;
    sty[i]=0.75+1.25*Math.random();
    f=document.createElement("div");
	f.style.position="absolute";
	f.setAttribute("id", "flk"+i);
	f.style.zIndex=i;
	f.style.top=yp[i]+"px";
	f.style.left=xp[i]+"px";
    k=document.createElement("img");
    k.src=flake_image;
	f.appendChild(k);
    b.appendChild(f);
  }
  setInterval("winter_snow()", speed);
}}
  
window.onresize=set_width;
function set_width() {
  if (document.documentElement && document.documentElement.clientWidth) {
    swide=document.documentElement.clientWidth;
    shigh=document.documentElement.clientHeight;
  }
  else if (typeof(self.innerHeight)=="number") {
    swide=self.innerWidth;
    shigh=self.innerHeight;
  }
  else if (document.body.clientWidth) {
    swide=document.body.clientWidth;
    shigh=document.body.clientHeight;
  }
  else {
	swide=800;
	shigh=600
  }
}

window.onscroll=set_scroll;
function set_scroll() {
  var sleft, sdown;
  if (typeof(self.pageYOffset)=="number") {
    sdown=self.pageYOffset;
    sleft=self.pageXOffset;
  }
  else if (document.body.scrollTop || document.body.scrollLeft) {
    sdown=document.body.scrollTop;
    sleft=document.body.scrollLeft;
  }
  else if (document.documentElement && (document.documentElement.scrollTop || document.documentElement.scrollLeft)) {
    sleft=document.documentElement.scrollLeft;
	sdown=document.documentElement.scrollTop;
  }
  else {
    sdown=0;
    sleft=0;
  }
  document.getElementById("bod").style.top=sdown+"px";
  document.getElementById("bod").style.left=sleft+"px";
}

function winter_snow() { 
  for (var i=0; i<flakes; i++) { 
    yp[i]+=sty[i];
    if (yp[i]>shigh-30) {
      xp[i]=am[i]+Math.random()*(swide-2*am[i]-25);
      yp[i]=0;
      sty[i]=0.75+1.25*Math.random();
    }
    dx[i]+=0.02+Math.random()/10;
    document.getElementById("flk"+i).style.top=yp[i]+"px";
    document.getElementById("flk"+i).style.left=(xp[i]+am[i]*Math.sin(dx[i]))+"px";
  }
}
// -->
