function changeBG(el,clr){
	var elem = document.getElementById(el);
	elem.style.transition = "background 1.0s linear 0s";
	elem.style.background = clr;
}

function fadeOut(el){
	var elem = document.getElementById(el);
	elem.style.transition = "opacity 0.5s linear 0s";
	elem.style.opacity = 0;
}

function fadeIn(el){
	var elem = document.getElementById(el);
	elem.style.transition = "opacity 0.5s linear 0s";
	elem.style.opacity = 1;
}

function slideOpen(el){
	var elem = document.getElementById(el);
	elem.style.transition = "height 0.2s linear 0s";
	elem.style.height = "200px";
}

function slideClosed(el){
	var elem = document.getElementById(el);
	elem.style.transition = "height 0.2s linear 0s";
	elem.style.height = "0px";
}

function slideIn(el){
	var elem = document.getElementById(el);
	elem.style.transition = "left 0.5s ease-in 0s";
	elem.style.left = "0px";
}

function slideOut(el){
	var elem = document.getElementById(el);
	elem.style.transition = "left 0.5s ease-out 0s";
	elem.style.left = "-400px";
}