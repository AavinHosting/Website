/*
This script adds classes of major browsers and operating system in body tag. 
Purpose of this script to enable writing different css based on browsers and operating systems.
@ author: Neetu Tyagi(neetu.iimt2007@gmail.com)
*/
ua=navigator.userAgent;
os=(navigator.platform).toLowerCase();
ap_ver=navigator.appVersion;
ua_cls="unknown_browser";
os_cls="unknown_os";
if(ua.indexOf('Windows')!=-1&&os.indexOf('win')!=-1){os_cls='windows';}
else if(ua.indexOf('Mac')!=-1&&ua.indexOf('iPhone')==-1){os_cls="mac";}
else if(ua.indexOf('Linux')!=-1){
	os_cls="linux";
	if(ua.indexOf('Fedora')!=-1)os_cls+=" fedora";
	else if(ua.indexOf('Ubuntu')!=-1)os_cls+=" ubuntu";
}
else if(ua.indexOf('Sun')!=-1){os_cls="sun";}
else if(ua.indexOf('Android')!=-1){	os_cls="android";}
else if(ua.indexOf('iPhone')!=-1){	os_cls="iphone";}
else if(ua.indexOf('iPad')!=-1){	os_cls="ipad";}
else if(ua.indexOf('Mobile')!=-1){	os_cls="mobile";}
else if(ua.indexOf('Symb')!=-1){os_cls="symb";}

if(ua.indexOf('MSIE')!=-1&&os.indexOf('win')!=-1){
	ua_cls="ie";
	ver=parseInt(ap_ver.split('MSIE')[1]);
	ua_cls+=" "+ua_cls+ver;
}
else if(ua.indexOf('Chrome')!=-1){
	ua_cls="chrome";
	ver=parseInt(ap_ver.split('Chrome/')[1]);
	ua_cls+=" "+ua_cls+ver;
}
else if(ua.indexOf('Firefox')!=-1){
	ua_cls="firefox";
	ver=parseFloat(ua.split('Firefox/')[1]);
	ua_cls+=" "+ua_cls+ver;
}
else if(ua.indexOf('Opera')!=-1){
	ua_cls="opera";
	ver=parseInt(ua.split('Version/')[1]);
	ua_cls+=" "+ua_cls+ver;
}
else if(ua.indexOf('Mozilla')!=-1&&ua.indexOf('Safari')!=-1){
	ua_cls="safari";	
	ver=parseInt(ua.split('Version/')[1]);
	ua_cls+=" "+ua_cls+ver;
	if(ua.indexOf('CriOS')!=-1){ua_cls="chrome";ver=parseInt(ua.split('CriOS/')[1]);
	ua_cls+=" "+ua_cls+ver;}
}
else if(ua.indexOf('Konqueror')!=-1){ua_cls="konqueror";}
else if(ua.indexOf('Mosaic')!=-1){ua_cls="mosaic";}
else if(ua.indexOf('Navigator')!=-1||ua.indexOf('Netscape')!=-1){ua_cls="netscape";}
else if(ua.indexOf('OmniWeb')!=-1){ua_cls="omniWeb";}


if( window.addEventListener ) {
  window.addEventListener("load",add_class,false);
} else if( document.addEventListener ) {
  document.addEventListener("load",add_class,false);
} else if( window.attachEvent ) {
	window.attachEvent("onload",add_class);
}

function add_class(){
body=document.getElementsByTagName('body')[0];
body.className=body.className+" "+ua_cls+" "+os_cls;
//alert(body.className+' '+ua+' '+os);
}




