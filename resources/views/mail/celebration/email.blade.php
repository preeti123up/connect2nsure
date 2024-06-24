
<?php
$man_flact_icon =   asset('user-uploads/celebration/femailicon.png');
$woman_flact_icon =  asset('user-uploads/celebration/maleIcon.png');
$companyLogo  = asset('user-uploads/app-logo/'.$user->company->logo);
if($user->gender =  "male"){
$flat_icon =  $man_flact_icon;
}else{
$flat_icon =  $woman_flact_icon;
}
if($user->image && $user->image !== NULL){
   $user_image = asset('user-uploads/avatar/'.$user->image);
}else{
   $user_image  = $flat_icon;
}

?>
<!-- New Templete -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="telephone=no" name="format-detection">
    <title></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div dir="ltr" class="es-wrapper-color">
        <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" bgcolor="#f5f5f5" style="padding: 10px;">
            <tbody>
                <tr>
                    <td valign="top" align="center" style="padding: 10px;">
                        <table cellpadding="0" cellspacing="0" width="600" bgcolor="#fff">
                            <tbody>
                                 <td align="center">
                                        <img width="120" src="{{$companyLogo}}" alt="">
                                     </td>
                            
                                <!-- Place the code for the photo at the top here -->
                                <tr>
                                    <td align="center" style="background-image: url({{asset('user-uploads/background_images/'.$settings->background_image)}}); background-size: cover; background-position: center; height: 378px;">
                                        <table cellpadding="0" cellspacing="0" align="center">
                                            <tbody>
                                                  <tr>
                                   
                                </tr>
                                                <tr>
                                                    <td align="center" style="border-radius: 50%; padding:10px;width: 130px; background-image: url({{asset('user-uploads/background_images/img-v.png')}});background-size: cover; background-position: center;">
                                                        <a target="_blank"><img style="padding: 5px; border-radius: 50%;" src="{{$user_image}}" alt width="130"></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="center" style="padding: 10px;">
                                                        <!-- <a target="_blank"><img style="padding: 5px; border: 2px rgb(255, 255, 255) dotted; border-radius: 50%;" src="https://eczufji.stripocdn.email/content/guids/CABINET_9dfdc91d9055fe3e58bb098162f9861d6f4f2db96de278e64f9c546ff3f6234a/images/e1f6925068dced36e436a53527255942.png" alt width="120"></a> -->
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="center" style="padding: 50px;">
                                                        <!-- <a target="_blank"><img style="padding: 5px; border: 2px rgb(255, 255, 255) dotted; border-radius: 50%;" src="https://eczufji.stripocdn.email/content/guids/CABINET_9dfdc91d9055fe3e58bb098162f9861d6f4f2db96de278e64f9c546ff3f6234a/images/e1f6925068dced36e436a53527255942.png" alt width="120"></a> -->
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="center" style="font-family: 'Montserrat', sans-serif;
                                                    font-size: 18px;
                                                    font-weight: 600;
                                                    line-height: 22px;
                                                    letter-spacing: 0em;
                                                    text-align: center;
                                                    color:{{ $settings->font_color }};
                                                    ">
                                                      <p>{{ $user->name }}</p>  
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <!-- End of photo code -->
                                <tr>
                                    <!-- Place the rest of your email content here -->
                                        <td align="center" style="padding:20px;">
                                        <table cellpadding="0" cellspacing="0" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td align="left" style="width: 70%;">
                                                        <h1 style="font-size: 25px; line-height: 150%; color: #000000;font-family: 'Montserrat', sans-serif;">Wishing you a Wonderful day</h1>
                                                        <p style="font-size: 16px;font-family: 'Montserrat', sans-serif; line-height: 22px; color:{{ $settings->font_color }};">{{$settings->message}}</p>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="left" style="border-top: 1px solid #d2cfcf;">
                                                        <p>&nbsp; &copy; 2024 All Rights Reserved.</p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        
    </div>
</body>

</html>