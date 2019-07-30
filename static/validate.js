function register_validate()
{
  if ( $("#username").val() == "")
  {
	  $("#username").parent().addClass("has-warning");
	  $("#username").parent().addClass("has-feedback");
	  $("#username").focus();
	  return false;
  }
  if ( $("#name").val() == "")
  {
	  $("#name").parent().addClass("has-warning");
	  $("#name").parent().addClass("has-feedback");
	  $("#name").focus();
	  return false;
  }
  if ( $("#password").val() == "")
  {
	  $("#password").parent().addClass("has-warning");
	  $("#password").parent().addClass("has-feedback");
	  $("#password").focus();
	  return false;
  }
  if ( $("#cpassword").val() == "")
  {
	  $("#cpassword").parent().addClass("has-warning");
	  $("#cpassword").parent().addClass("has-feedback");
	  $("#cpassword").focus();
	  return false;
  }
  if ( $("#password").val() != $("#cpassword").val() )
  {
	  alert("passwords don't match");
	  return false;
  }
  return true;
}

function login_validate()
{
	if ( $("#username").val() == "")
	{
	  $("#username").parent().addClass("has-warning");
	  $("#username").parent().addClass("has-feedback");
	  $("#username").focus();
	  return false;
	}
	if ( $("#password").val() == "" )
	{
		$("#password").parent().addClass("has-warning");
		$("#password").parent().addClass("has-feedback");
		$("#password").focus();
		return false;
	}
	return true;
}