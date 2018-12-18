/* jquery object that hides or shows the login and register forms when clicked */
$(document).ready(function() {

	/* hide login form, show register form */
	$("#hideLogin").click(function() {
		$("#loginForm").hide();
		$("#registerForm").show();
	});

	/* hide register form, show login form */
	$("#hideRegister").click(function() {
		$("#loginForm").show();
		$("#registerForm").hide();
	});
});