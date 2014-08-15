var lastTapTime;

/*
 Esta funcion verfica si el tap lo hizo el usuario o si
 fue el bug de jquery quien lo activo.
 
 Si fue un tap fantasma retorna true, sino retorna false.
 */
function isJqmGhostClick(event) {
    var currTapTime = new Date().getTime();

    if (lastTapTime == null || currTapTime > (lastTapTime + 1000)) {
        lastTapTime = currTapTime;
        return false;
    }
    else {
        return true;
    }
}

/*
 var lastclickpoint, curclickpoint;
 var isJqmGhostClick = function(event) {
 curclickpoint = event.clientX + 'x' + event.clientY;
 var ret = false;
 if (lastclickpoint === curclickpoint) {
 ret = true;
 } else {
 ret = false;
 }
 lastclickpoint = curclickpoint;
 return ret;
 }*/