var lastTapTime;

/*
 Esta funcion verfica si el tap lo hizo el usuario o si
 fue el bug de jquery quien lo activo.
 
 Si fue un tap fantasma retorna true, sino retorna false.
 */
function isJqmGhostClick(event) {
    var currTapTime = new Date().getTime();
    console.log("Curent ", currTapTime);
    if (lastTapTime == null || currTapTime > (lastTapTime + 300)) {
        lastTapTime = currTapTime;
        return false;
    }
    else {
        return true;
    }
}
