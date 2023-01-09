<?php
	class DashboardController extends Controller
	{
		public function __construct()
		{

		}

		public function index()
		{
			$this->data['page_title'] = 'Dashboard';
			return $this->view('tmp/maintenance', $this->data);
		}
	}