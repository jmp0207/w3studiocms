/*
 * This file is part of the w3studioCMS package library and it is distributed 
 * under the LGPL LICENSE Version 2.1. To use this library you must leave 
 * intact this copyright notice.
 *  
 * (c) 2007-2008 Giansimon Diblas <giansimon.diblas@w3studiocms.com>
 *  
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

var w3sTools = Class.create({
	
	drawOverlayer: function()
	{
	  if ($('w3s_overlay') == null){
	    var objBody = document.getElementsByTagName("body").item(0);
	    var objOverlay = document.createElement("div");
	    objOverlay.setAttribute('id','w3s_overlay');
	    objOverlay.style.display = 'none';
	    objBody.appendChild(objOverlay);
	  }
	},
	
	getPageSize: function(){
  
	  var xScroll, yScroll;
	  
	  if (window.innerHeight && window.scrollMaxY) {  
	    xScroll = window.innerWidth + window.scrollMaxX;
	    yScroll = window.innerHeight + window.scrollMaxY;
	  } else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
	    xScroll = document.body.scrollWidth;
	    yScroll = document.body.scrollHeight;
	  } else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
	    xScroll = document.body.offsetWidth;
	    yScroll = document.body.offsetHeight;
	  }
	  
	  var windowWidth, windowHeight;
	  if (self.innerHeight) {  // all except Explorer
	    if(document.documentElement.clientWidth){
	      windowWidth = document.documentElement.clientWidth; 
	    } else {
	      windowWidth = self.innerWidth;
	    }
	    windowHeight = self.innerHeight;
	  } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
	    windowWidth = document.documentElement.clientWidth;
	    windowHeight = document.documentElement.clientHeight;
	  } else if (document.body) { // other Explorers
	    windowWidth = document.body.clientWidth;
	    windowHeight = document.body.clientHeight;
	  }  
	  
	  // for small pages with total height less then height of the viewport
	  if(yScroll < windowHeight){
	    pageHeight = windowHeight;
	  } else { 
	    pageHeight = yScroll;
	  }
	
	  // for small pages with total width less then width of the viewport
	  if(xScroll < windowWidth){  
	    pageWidth = xScroll;    
	  } else {
	    pageWidth = windowWidth;
	  }
	
	  arrayPageSize = new Array(pageWidth,pageHeight,windowWidth,windowHeight) 
	  return arrayPageSize;
	},
	
	openFileUploader: function()
	{
	  this.drawOverlayer();
	  
	  var arrayPageSize = this.getPageSize();
	  $('w3s_overlay').style.width = arrayPageSize[0] + 'px';
	  $('w3s_overlay').style.height = arrayPageSize[1] + 'px';
	  new Effect.Appear('w3s_overlay', { from: 0.3, to: 0.5 });
	  
	  var params = $A(arguments);
	  var param = (params[0] != undefined) ? '?cfgFile='+params[0] : '';
	  W3sWindow.openStandard("/js/fileUploader/fileuploader.php" + param, "", 670, 400);
	},
	
	/** 
	 * Changes the page's stylesheets.
	 *
	 * @param templateStyles The styles that belong the template. The format
	 *                       is style1,[style2,style3,...]
	 */ 
	temaChange: function(templateStyles){
	  try{  
	    if (templateStyles == undefined) templateStyles = '';
	    if(templateStyles == ''){
	      // disattiva tutti gli stili con il title
	      W3sTools.templateChange();
	    }else{
	      // attiva soltanto lo stile scelto
	      eval("W3sTools.templateChange('" + templateStyles + "')");
	    }
	  }
	  catch(e){
	    alert(e);
	  }
	},
	
	// La funzione per cambiare i fogli di stile
	templateChange: function(strMatch){
	  //controllo browser
	  if(!document.styleSheets)
	  {
	    var ss = getAllSheets() //Opera
	  }
	  else
	  {
	    var ss = document.styleSheets; //Dom
	  }
	
	  // disabilita tutti i fogli di stile con un titolo 
	  // tranne quello passato per argomento alla funzione
	  for( var x = 0; x < ss.length; x++ )
	  {
	    sTitle = ss[x].title; 
	    if (sTitle != '')
	    {
	      ss[x].disabled = (!strMatch.match(sTitle + ',')) ? true : false;
	    }
	  }
	  if( !ss.length )
	  { 
	    alert( 'You browser cannot change the stylesheets. Please use Mozilla Firefox' );
	  }
	},
	
	// Funzione per Opera
	getAllSheets: function(){
	  if( document.getElementsByTagName ) {
	    var Lt = document.getElementsByTagName('LINK');
	    var St = document.getElementsByTagName('STYLE');
	  } else {
	    // browser minori - restituisce array vuoto
	    return []; 
	  }
	  //per tutti i tag link ...
	  for( var x = 0, os = []; Lt[x]; x++ ) {
	    //controlla l'attributo rel per vedere se contiene 'style'
	    if( Lt[x].rel ) {
	      var rel = Lt[x].rel;
	    } else if( Lt[x].getAttribute ) {
	      var rel = Lt[x].getAttribute('rel');
	    } else {
	      var rel = '';
	    }
	    if(typeof(rel)=='string'&&rel.toLowerCase().indexOf('style')+1){
	      //riempe la variabile os con i stylesheets linkati
	      os[os.length] = Lt[x];
	    }
	  }
	  //include anche tutti i tags style e restituisce l'array
	  for( var x = 0; St[x]; x++ ) {
	    os[os.length] = St[x];
	  }
	  return os;
	},
	
	resetFormObjects: function(sFormName){ 
	  var inputs = $(sFormName).getInputs();
	  inputs.each(function(input){
	    input.clear();
	  });
	  
	  return false;
	},
	
	centerElement: function(element, iTop, iLeft)
	{
	    try
	    {
	        element = $(element);
	    }
	    catch(e)
	    {
	        return;
	    }
	
	    var my_width  = 0;
	    var my_height = 0;
	
	    if ( typeof( window.innerWidth ) == 'number' )
	    {
	
	        my_width  = window.innerWidth;
	        my_height = window.innerHeight;
	    }
	    else if ( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) )
	    {
	
	        my_width  = document.documentElement.clientWidth;
	        my_height = document.documentElement.clientHeight;
	    }
	    else if ( document.body && ( document.body.clientWidth || document.body.clientHeight ) )
	    {
	
	        my_width  = document.body.clientWidth;
	        my_height = document.body.clientHeight;
	    }
	
	    var scrollY = 0;
	
	    if ( document.documentElement && document.documentElement.scrollTop )
	    {
	        scrollY = document.documentElement.scrollTop;
	    }
	    else if ( document.body && document.body.scrollTop )
	    {
	        scrollY = document.body.scrollTop;
	    }
	    else if ( window.pageYOffset )
	    {
	        scrollY = window.pageYOffset;
	    }
	    else if ( window.scrollY )
	    {
	        scrollY = window.scrollY;
	    }
	
	    var elementDimensions = Element.getDimensions(element);
	
	    var setX;
	    var setY;
	
	    if (iLeft == undefined){
	      setX = ( my_width  - elementDimensions.width  ) / 2;
	      setX = ( setX < 0 ) ? 0 : setX;
	    }
	    else{
	      setX = iLeft;
	    }
	
	    if (iTop == undefined){
	      setY = ( my_height - elementDimensions.height ) / 2 + scrollY;
	      setY = ( setY < 0 ) ? 0 : setY;
	    }
	    else{
	      setY = iTop;
	    }
	
	    element.style.left = setX + "px";
	    element.style.top  = setY + "px";
	},
	
	opacize: function(sElement, dOpacity){
	  if ($(sElement) != null){$(sElement).style.opacity = dOpacity;}
	},
	
	checkBrowser: function(){
	  var agt = navigator.userAgent.toLowerCase();
	  var res = (((agt.indexOf("msie") != -1) && (agt.indexOf("opera") == -1)) ? "ie" : "others");
	  return res; 
	},
	
	updateJSON: function(request, json)
	{
	  try{
	    var sValue; 
	    var nbElementsInResponse = json.length; 
	    for (var i = 0; i < nbElementsInResponse; i++)
	    { 
	      if ($(json[i][0]) != null){ 
	        sValue = (json[i][1] != null) ? json[i][1] : '';
	        switch ($(json[i][0]).type){
	          case "text": 
	            $(json[i][0]).value = sValue;
	            break;
	          case "textarea": 
	            $(json[i][0]).value = sValue;
	            break;
	          case "hidden":
	            $(json[i][0]).value = sValue;
	            break;
	          case "select-one":
	            try{
	              $(json[i][0]).selectedIndex = (sValue - 1);
	            }
	            catch(e){
	              $(json[i][0]).selectedIndex = this.getSelectIndexFromValue('w3s_ppt_int_link', sValue)
	            }
	            break;
	          default:
	            Element.update(json[i][0], sValue);
	        }
	      }
	    }
	  
	  }
	  catch(e){
	    alert('updateJSON: ' + e);
	  }
	},
	
	getSelectIndexFromValue: function(selectName, sValue){
	  var i;
	  var result=-1;
	  for(i=0;i<$(selectName).length-1;i++){
	    if ($(selectName).options[i].value == sValue){
	      result = i;
	      break;
	    }
	  }
	
	  return result;
	}
	
});