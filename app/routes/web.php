<?php
	
	$routes = [];

	$controller = '/MailerController';
	$routes['mailer'] = [
		'index' => $controller.'/index',
		'edit' => $controller.'/edit',
		'create' => $controller.'/create',
		'delete' => $controller.'/destroy',
		'send'   => $controller.'/send'
	];

	$controller = '/ViewerController';
	$routes['viewer'] = [
		'index' => $controller.'/index',
		'show' => $controller.'/show',
		'edit' => $controller.'/edit',
		'create' => $controller.'/create',
		'delete' => $controller.'/destroy',
	];

	$controller = '/UserController';
	$routes['user'] = [
		'index' => $controller.'/index',
		'edit' => $controller.'/edit',
		'create' => $controller.'/create',
		'delete' => $controller.'/destroy',
		'show'   => $controller.'/show',
		'sendCredential' => $controller.'/sendCredential'
	];

	$controller = '/AuthController';
	$routes['auth'] = [
		'login' => $controller.'/login',
		'register' => $controller.'/register',
		'logout' => $controller.'/logout',
		'code'  => $controller.'/code'
	];

	$controller = '/AttachmentController';
	$routes['attachment'] = [
		'index' => $controller.'/index',
		'edit' => $controller.'/edit',
		'create' => $controller.'/create',
		'delete' => $controller.'/destroy',
		'download' => $controller.'/download',
		'show'   => $controller.'/show'
	];

	$controller = '/ReceiptController';
	$routes['receipt'] = [
		'index' => $controller.'/index',
		'order' => $controller.'/orderReceipt',
		'create' => $controller.'/create',
		'delete' => $controller.'/destroy',
		'show'   => $controller.'/show',
	];

	$controller = '/ItemController';
	$routes['item'] = [
		'index' => $controller.'/index',
		'edit' => $controller.'/edit',
		'create' => $controller.'/create',
		'delete' => $controller.'/destroy',
		'show'   => $controller.'/show',
		'read'   => $controller.'/read',
		'like'   => $controller.'/like',
		'my-catalog' => $controller.'/myCatalog',
		'catalogs'  => $controller. '/catalogs',
		'add-attachment' => $controller .'/addAttachment'
	];

	$controller = '/ItemCommentController';
	$routes['item-comment'] = [
		'index' => $controller.'/index',
		'edit' => $controller.'/edit',
		'create' => $controller.'/create',
		'delete' => $controller.'/destroy',
		'show'   => $controller.'/show',
		'like'   => $controller.'/like',
	];
	

	$controller = '/CategoryController';
	$routes['category'] = [
		'create' => $controller.'/create',
		'index' => $controller.'/index',
		'edit' => $controller.'/edit',
		'order' => $controller.'/orderReceipt',
		'delete' => $controller.'/destroy',
		'show'   => $controller.'/show',
		'logs' => $controller.'/logs',
		'deactivate' => $controller.'/deactivateOrActivate',
		'remove' => $controller. '/remove'
	];

	$controller = '/DashboardController';
	$routes['dashboard'] = [
		'index' => $controller.'/index',
		'edit' => $controller.'/edit',
		'create' => $controller.'/create',
		'create' => $controller.'/create',
		'delete' => $controller.'/destroy',
		'show'   => $controller.'/show',
		'update' => $controller.'/update',
		'phyical-examination' => $controller. '/phyicalExamination'
	];

	$controller = '/ReportController';
	$routes['report'] = [
		'index' => $controller.'/index',
		'sales' => $controller.'/salesReport',
		'stocks' => $controller.'/stocksReport',
		'create' => $controller.'/create',
		'delete' => $controller.'/destroy',
		'download' => $controller.'/download',
		'show'   => $controller.'/show',
		'live'   => $controller.'/live',
		'new'    => $controller.'/new',
		'serve'  => $controller.'/serve',
		'skip'   => $controller.'/skip',
		'complete' => $controller.'/complete',
		'reset'   => $controller.'/reset'
	];
	
	$controller = '/FeedController';
	$routes['feed'] = [
		'index' => $controller.'/index',
		'edit' => $controller.'/edit',
		'create' => $controller.'/create',
		'delete' => $controller.'/destroy',
		'download' => $controller.'/download',
		'show'   => $controller.'/show'
	];



	$controller = '/FormBuilderController';
	$routes['form'] = [
		'index' => $controller.'/index',
		'edit' => $controller.'/edit',
		'create' => $controller.'/create',
		'delete' => $controller.'/destroy',
		'show'   => $controller.'/show',
		'add-item' => $controller.'/addItem',
		'edit-item' => $controller. '/editItem',
		'delete-item' => $controller. '/deleteItem',
		'respond'   => '/FormController'.'/respond'
	];

	return $routes;
?>