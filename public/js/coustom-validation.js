function name(value,msg,fildName,hide)
 {

    if (hide.length > 0) {
        hide.forEach(function(h) {
          $("#sp_" + h).hide();
          $("#" + h).css("border", "");
       });
    }

    if(value=='')
    {
         $("#sp_"+fildName).show();
         $("#sp_"+fildName).css('color','red');
         $("#sp_"+fildName).html('enter '+msg);
         $("#"+fildName).css("border", "1px solid red");
         return false;
    }

    if(value.length<3)
    {
         $("#sp_"+fildName).show();
         $("#sp_"+fildName).css('color','red');
         $("#sp_"+fildName).html(msg+" must be at least 2 characters long");
         $("#"+fildName).css("border", "1px solid red");
         return false;
    }

    if (!isNaN(value.charAt(0))) {
        $("#sp_"+fildName).show();
        $("#sp_"+fildName).css('color','red');
        $("#sp_"+fildName).html(msg+" is not a valid name");
        $("#"+fildName).css("border", "1px solid red");
        return false;
     }

     return true;


 }

 function onlyName(value,msg,fildName,hide)
 {

    if (hide.length > 0) {
        hide.forEach(function(h) {
          $("#sp_" + h).hide();
          $("#" + h).css("border", "");
       });
    }

    if(value=='')
    {
         $("#sp_"+fildName).show();
         $("#sp_"+fildName).css('color','red');
         $("#sp_"+fildName).html('enter '+msg);
         $("#"+fildName).css("border", "1px solid red");
         return false;
    }


     return true;


 }




 function number(value,msg,fildName,hide)
 {
    if (hide.length > 0) {
      hide.forEach(function(h) {
        $("#sp_" + h).hide();
        $("#" + h).css("border", "");
     });
    }

    if(value=='')
    {
         $("#sp_"+fildName).show();
         $("#sp_"+fildName).css('color','red');
         $("#sp_"+fildName).html('enter '+msg);
         $("#"+fildName).css("border", "1px solid red");
         return false;
    }
    if (isNaN(value)) {
        $("#sp_"+fildName).show();
        $("#sp_"+fildName).css('color','red');
        $("#sp_"+fildName).html(msg+" is not a valid");
        $("#"+fildName).css("border", "1px solid red");
        return false;
    }


    return true;

 }

 function selectBox(value,msg,fildName,hide)
 {

    if (hide.length > 0) {
        hide.forEach(function(h) {
          $("#sp_" + h).hide();
          $("#" + h).css("border", "");
       });
    }

    if(value=='')
    {
         $("#sp_"+fildName).show();
         $("#sp_"+fildName).css('color','red');
         $("#sp_"+fildName).html('select '+msg);
         $("#"+fildName).css("border", "1px solid red");
         return false;
    }

     return true;


 }
