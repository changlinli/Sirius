<?php
    class facebook extends basecontroller {
    	
    	function init() {
			$this->facebook 	= new dp_graph($this->registry, false);
			if($this->facebook->auth()) {
				$this->facebook->access_token = $_SESSION['access_token'];
			}
			$this->config['app_layout'] = 'facebook.php';
        }
        
        function index_action() {
        	if($this->facebook->auth()) {
				return array('result'=>'true');
			} else {
				return array('result'=>'false', 'title'=>'Welcome', 'data'=>$this->facebook->url());
			}
        }
		
		function pages_action() {
			if($this->facebook->auth()) {
				$pages = $this->facebook->pages();	
				return array('result'=>'true', 'title'=>'My Pages', 'data'=>$pages);
			} else {
				die(header('Location:/facebook/index'));
			}
		}
		
		function insights_action() {
			$this->insights = new dp_insights($this->registry, false);
			$this->insights->insights_app_id 		= '344725718882692';
			$this->insights->access_token 			= 'AAADU46qCHToBACPT93vBWip2wL6TND05pVe9VFkZAcjOOTabXBKyTkgwJmZB8j4uHakuOsuNhpHzJmuEZCq0YbUSZA6LWFrHLsMzrR8GvV3cgkQTLvLH';
			
			$page_impressions 		= $this->insights->insight('page_impressions','day',array('since'=>strtotime("-7 days"),'until'=>strtotime("now")));
			$page_engaged_users 	= $this->insights->insight('page_engaged_users','day',array('since'=>strtotime("-7 days"),'until'=>strtotime("now")));
			$page_negative_feedback = $this->insights->insight('page_negative_feedback_by_type_unique','day',array('since'=>strtotime("-7 days"),'until'=>strtotime("now")));
			$posts = $this->insights->graph($this->insights->insights_app_id,'posts');
			
			return array('result'=>'true', 
						'title'=>'My Page Insights', 
						'page_impressions'=>$page_impressions,
						'page_engaged_users'=>$page_engaged_users,
						'page_negative_feedback'=>$page_negative_feedback,
						'posts'=>$posts);
		}
		
		function me_action() {
			if($this->facebook->auth()) {
				$me = $this->facebook->me();	
				return array('result'=>'true', 'title'=>'My Data', 'data'=>$me);
			} else {
				die(header('Location:/facebook/index'));
			}
		}
		
		function friends_action() {
			if($this->facebook->auth()) {
				$friends = $this->facebook->friends();	
				return array('result'=>'true', 'title'=>'My Friends Data', 'data'=>$friends);
			} else {
				die(header('Location:/facebook/index'));
			}
		}
		
		function auth_action() {
			$this->facebook->initialize();
			die(header('Location:/facebook/index'));
		}
    }