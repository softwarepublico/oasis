// Ex: onKeyPress="return semCaracterEspecial(event)"
function semCaracterEspecial( e ){
	if(window.event) { _TXT = e.keyCode; }
    else if(e.which) { _TXT = e.which; }

    if( (_TXT != 34) &&
    	(_TXT != 42) &&
    	(_TXT != 47) &&
    	(_TXT != 58) &&
    	(_TXT != 60) &&
    	(_TXT != 62) &&
    	(_TXT != 63) &&
    	(_TXT != 92) &&
    	(_TXT != 124)
       ){
       return true;
    } else {
       return false;
    }
}
