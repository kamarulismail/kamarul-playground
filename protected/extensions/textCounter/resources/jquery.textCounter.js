/*
 * jQuery Text Counter Plugin v 2.0
 * Copyright (c) 2010 Kamarul Ariffin Ismail
 * Version: 1.0 (27/02/2011)
 * Requires: jQuery v1.4.2 or later
 */
(function($){
  $.fn.TextCounter = function(options, fn) {
    var defaults = {
      maxCharacterSize: -1,
      displayFormat   : '#totalChar characters',
      displayCounter  : '#counter',
      displayTemplate : '<div>{displayFormat}</div>'
    };

    var options = $.extend(defaults, options);
  
    var container = $(this);
    if(container.length == 0) {return true;}

    container.bind('keydown', function(event){return countByCharacter(event.keyCode);});
    container.bind('paste'  , function(event){setTimeout(function(){countByCharacter('PASTE');}, 10);});
    container.bind('keyup'  , function(event){setTimeout(function(){refreshCounter();}, 5);});

    var totalChar     = 0;
    var totalMaxChar  = options.maxCharacterSize;
    var totalCharLeft = (options.maxCharacterSize > 0) ? options.maxCharacterSize : 0;
    var containerDisplay = $(options.displayCounter);
  
    refreshCounter();

    function updateCounter(){
      containerDisplay = $(options.displayCounter);      
      if(containerDisplay.length > 0){
        containerDisplay.html(formatDisplayInfo());
      }
      else{
        var containerDisplayID = options.displayCounter.replace('#',''); 
        
        displayTemplate = options.displayTemplate;
        displayTemplate = displayTemplate.replace('{displayFormat}', formatDisplayInfo());
        containerDisplay = $(displayTemplate);
        containerDisplay.attr('id', containerDisplayID );
        containerDisplay.attr('name', containerDisplayID );
       
        /*
        if (options.errorMessageID != '') 
          $(options.errorMessageID).after(containerDisplay);
        else 
          $(container).after(containerDisplay);
        */
        
        if (options.errorMessageID != '') {
            if ($(options.errorMessageID).is(":visible")) $(options.errorMessageID).after(containerDisplay);
            else $(container).after(containerDisplay);
        }
          
        else {
            $(container).after(containerDisplay);
        }
      }
    }

    function refreshCounter(){
      var contents      = container.val();
      var contentLength = contents.length;
      totalMaxChar  = options.maxCharacterSize;
      totalChar     = contentLength;
      totalCharLeft = (totalMaxChar - contentLength);
      updateCounter();
    }

    function countByCharacter(keyCode){      
      var content       = container.val();
      var contentLength = content.length;

      totalMaxChar  = options.maxCharacterSize;
      totalChar     = contentLength;
      totalCharLeft = (totalMaxChar - contentLength);
                    
      if(keyCode == 'INITIAL' || keyCode == 'KEYUP'){
        updateCounter();
        return true;
      }
      else if(keyCode == 17 || keyCode == 16 || keyCode == 20){ /* CTRL, SHIFT, CAPS */
        return true;
      }
      else{
        if(keyCode == 8 || keyCode == 46){ /* DELETE, BACKSPACE */
          if(contentLength > 0){
            contentLength--;
          }
          else{
            contentLength = 0;
          }
          
          totalChar     = contentLength;
          totalCharLeft = (totalMaxChar - contentLength);
          return true;
        }
        else{
          contentLength++;
          
          if(options.maxCharacterSize > 0){
            if(keyCode == 'PASTE'){
              content = content.substring(0, options.maxCharacterSize);
              container.val(content);
              contentLength = content.length;
              
              totalChar     = contentLength;
              totalCharLeft = (totalMaxChar - contentLength);
              return true;
            }
            else if(contentLength > options.maxCharacterSize) {
              contentLength = options.maxCharacterSize;
              totalCharLeft = 0;
              return false;
            }
          } /* if(options.maxCharacterSize > 0) */
          
          totalChar     = contentLength;
          totalCharLeft = (totalMaxChar - contentLength);
        }
      }
      return true;
    }
    
    function formatDisplayInfo(){
      var format = options.displayFormat;
      format = format.replace('#totalChar', totalChar);
			
      /*WHEN totalMaxChar <= 0, #totalMax, #totalLeft cannot be substituted. */
      if(totalMaxChar > 0){
        format = format.replace('#totalMax' , totalMaxChar);
        format = format.replace('#totalLeft', totalCharLeft);
      }
      return format;
    }
    
  };
})(jQuery); 