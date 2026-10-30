<html>
<body>
  <div style="display: flex;">
    <div style="width: 100%; max-width: 440px; padding: 0 20px;">
      <div style="width: 100%; padding: 40px 7px;"><img style="width: 150px;" src="https://phplaravel-1173480-4106138.cloudwaysapps.com/images/alicor/alicor-logo.png" /></div>
      <div style="max-width: 100%; background-color: #f1f1f1; padding: 20px 16px; font-weight: bold; font-size: 20px; color: #161823;">Reset your password.&nbsp;</div>
      <div style="max-width: 100%; background-color: #f8f8f8; padding: 24px 16px; font-size: 17px; color: rgba(22,24,35,0.75); line-height: 20px;">
        <p style="margin-bottom: 20px;">To rest your password, please click the button below:<br /><br /></p>
        <p style="margin-bottom: 20px; color: #161823; font-weight: bold; text-align: center;"><a style="border-radius: 50px; font-size: 14px; line-height: 19px; color: #FFF; font-weight: bold; background-color: #1E1E1E; padding: 17px 30px 17px 30px; text-decoration: none;" rel="noreferrer" href="{{url('/reset-pwd?email='.$email.'&member_id='.$id.'')}}" target="_blank">Reset password</a></p>
        <p style="margin-bottom: 20px;"><br />The reset password link will expire after 48 hours.</p>
        <p style="margin-bottom: 20px;">If you didn't request for this, please ignore this message.</p>
      </div>
    </div>
  </div>
</body>
</html>
