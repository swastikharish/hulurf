<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] 		= 'forum';
$route['404_override'] = 'page';
$route['translate_uri_dashes'] 	= FALSE;

// Home
$route['home'] 							 		= 'forum/index';

// Registration
$route['signup'] 						 		= 'register/form';
$route['register/success'] 	 		= 'register/success';
$route['register']							= 'register/form_save';
$route['email/validate'] 				= 'register/validate_email';

// Login
$route['logout'] 		            = 'login/logout';
$route['login'] 						  	= 'login/login_form';
$route['ajax/login'] 					  = 'login/login_request';

//////////////*****User Panel*****////////////////
$route['dashboard']							= 'dashboard/index';

// Forums
$route['forum'] 													= 'forum/index';
$route['forum/topic/conversation'] 				= 'topic/conversation_save';

//////////////*****Admin Panel*****//////////////// 
$route['admin']											= 'admin/dashboard/index';
$route['admin/dashboard']						= 'admin/dashboard/index';
$route['admin/logout']							=	'admin/login/logout';
$route['admin/login']								=	'admin/login/index';
$route['admin/ajax/login']    			= 'admin/ajax/login';
$route['admin/ajax/update/table'] 	= 'admin/ajax/update_table';

//////////////*****User*****//////////////
$route['admin/users']   						= 'admin/user/directory';
$route['admin/user/add']           	= 'admin/user/form';
$route['admin/user/edit/(:num)']   	= 'admin/user/form/$1';
$route['admin/user/delete/(:num)'] 	= 'admin/user/delete/$1';
$route['admin/user/logs/(:num)']   	= 'admin/user/logs/$1';
$route['admin/ajax/user/save']   		= 'admin/user/save';

//////////////*****Setting*****//////////////
$route['admin/setting']	   								= 'admin/setting/index';
$route['admin/setting/global']	   				= 'admin/setting/global_form';
$route['admin/ajax/setting/global/save']  = 'admin/setting/global_form_save';

//////////////*****Image*****//////////////
$route['admin/images']	   				 				= 'admin/image/directory';
$route['admin/image/add']           			= 'admin/image/form';
$route['admin/image/edit/(:num)']   			= 'admin/image/form/$1';
$route['admin/image/delete/(:num)'] 			= 'admin/image/delete/$1';
$route['admin/ajax/image/save']  					= 'admin/image/save';
$route['admin/image/remove/image/(:num)'] = 'admin/image/image_remove/$1';

//////////////*****Video*****//////////////
$route['admin/videos']	   				 			= 'admin/video/directory';
$route['admin/video/add']           		= 'admin/video/form';
$route['admin/video/edit/(:num)']   		= 'admin/video/form/$1';
$route['admin/video/delete/(:num)'] 		= 'admin/video/delete/$1';
$route['admin/ajax/video/save']  		    = 'admin/video/save';

//////////////*****Category*****//////////////
$route['admin/categories']	   				 		= 'admin/category/directory';
$route['admin/category/add']           		= 'admin/category/form';
$route['admin/category/edit/(:num)']   		= 'admin/category/form/$1';
$route['admin/category/delete/(:num)'] 		= 'admin/category/delete/$1';
$route['admin/ajax/category/save']  			= 'admin/category/save';

//////////////*****Forum*****//////////////
$route['admin/forums']	   				 				= 'admin/forum/directory';
$route['admin/forum/add']           			= 'admin/forum/form';
$route['admin/forum/edit/(:num)']   			= 'admin/forum/form/$1';
$route['admin/forum/delete/(:num)'] 			= 'admin/forum/delete/$1';
$route['admin/ajax/forum/save']  					= 'admin/forum/save';
$route['admin/forum/remove/image/(:num)/(:num)'] 	= 'admin/forum/image_remove/$1/$2';
$route['admin/forum/remove/video/(:num)/(:num)'] 	= 'admin/forum/video_remove/$1/$2';

//////////////*****Topic*****//////////////
$route['admin/topics']	   				 		    = 'admin/topic/directory';
$route['admin/topic/add']           			= 'admin/topic/form';
$route['admin/topic/add/(:num)']          = 'admin/topic/form/$1';
$route['admin/topic/edit/(:num)/(:num)']  =	'admin/topic/form/$1/$2';
$route['admin/topic/delete/(:num)'] 			= 'admin/topic/delete/$1';
$route['admin/ajax/topic/save']  					= 'admin/topic/save';
$route['admin/topic/remove/image/(:num)/(:num)/(:num)'] = 'admin/topic/image_remove/$1/$2/$3';
$route['admin/topic/remove/pdf/(:num)/(:num)'] = 'admin/topic/pdf_remove/$1/$2';

$route['admin/forum/approved/(:any)'] 		= 'admin/post/forum_approved/$1';
$route['admin/forum/(:any)'] 							= 'admin/post/forum/$1';
$route['admin/topic/approved/(:any)'] 		= 'admin/post/topic_approved/$1';
$route['admin/topic/(:any)'] 							= 'admin/post/topic/$1';
$route['admin/ajax/conversation/save'] 		= 'admin/post/conversation_save';

//////////////*****Page*****//////////////
$route['admin/pages']	   	      	= 'admin/page/directory';
$route['admin/page/add']          = 'admin/page/form';
$route['admin/page/edit/(:num)']  = 'admin/page/form/$1';
$route['admin/ajax/page/save']    = 'admin/page/save';
$route['admin/ajax/delete-page']  = 'admin/page/delete_page';



