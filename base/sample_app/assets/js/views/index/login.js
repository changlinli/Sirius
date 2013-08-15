$(function(){
	var app = new AppView({form: '#login', el: $('#login #sign-in'), element: 'sign-in', action: '/auth/auth.json'});
});
