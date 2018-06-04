<?php

// This is to handle the coming soon page
$app->get('/coming_soon', 'Coming_soonController:index')->setName('coming_soon');

// This is to handle going to normal users home page
$app->get('/', 'HomeController:index')->setName('home');

//This is to handle going to admin users dashboard
$app->get('/admin/panel', 'PanelController:index')->setName('dashboard');

//This is to handle signin up normal users
$app->get('/auth/user/signup', 'UserAuthController:getSignUp')->setName('user_auth.signup');
$app->post('/auth/user/signup', 'UserAuthController:postSignUp');

//This is to handle signin in normal users
$app->get('/auth/user/signin', 'UserAuthController:getSignIn')->setName('user_auth.signin');
$app->post('/auth/user/signin', 'UserAuthController:postSignIn');

//This is to handle signin out normal users
$app->get('/auth/user/signout', 'UserAuthController:getSignOut')->setName('user_auth.signout');

//This is to handle signin up admin users
$app->get('/auth/admin/signup', 'AdminAuthController:getSignUp')->setName('admin_auth.signup');
$app->post('/auth/admin/signup', 'AdminAuthController:postSignUp');

//This is to handle signin in admin users
$app->get('/auth/admin/signin', 'AdminAuthController:getSignIn')->setName('admin_auth.signin');
$app->post('/auth/admin/signin', 'AdminAuthController:postSignIn');

//This is to handle signin out admin users
$app->get('/auth/admin/signout', 'AdminAuthController:getSignOut')->setName('admin_auth.signout');

//This is to handle api call to activate admin account, still needs securing
$app->post('/auth/admin/activate', 'AdminAuthController:sendVerificationLink');

?>