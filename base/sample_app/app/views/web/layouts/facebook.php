			<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    
    <head>
        <title>Facebook Tools</title>
        <link type="text/css" rel="stylesheet" media="all" href="/css/global.css" />
        <script type="text/javascript" src="/js/jquery-1.6.1.min.js"></script>
		<script type="text/javascript" src="/js/jquery.tools.min.js"></script>
		<script type="text/javascript" src="/js/jquery.effects.core.js"></script>
		<script type="text/javascript" src="/js/global.js"></script>
    </head>
    <!--layout.php-->
    <body class="">
        <!-- APP HEADER AND NAV -->
        <div class="dl-fixed-width-980">
            <div class="dl-flex-column-100">
                <div class="dl-font-color-black dl-padding-20" style="padding-left: 0px; padding-right: 0px;">  
                    <div class="dl-float-left dl-font-size-20">Facebook Tools</div>

                    <div class="dl-float-right dl-margin-top-5">Welcome, <span class="dl-font-bold"><?php echo $_SESSION['user']['first_name']; ?></span>!  |  <a class="dl-grey-link" href="/auth/logout">Log Out</a></div>
                    <div class="dl-column-clear"></div>
                </div>
            </div>
            <div class="dl-column-clear"></div>
        </div>
        <div class="dl-gradient-grey dl-flex-column-100 dl-horiz-menu-border">
            <div class="dl-fixed-width-980">
                <ul class="dl-horiz-menu">
                    <li><a href="/facebook/index">Home</a></li>
                </ul>
            </div>
        </div>
        <div class="dl-column-clear"></div>
        <!-- END APP HEADER AND NAV -->

        <!-- START 980 WRAP-->
        <div class="dl-fixed-width-980">	
				
				<?php echo $dp_content; ?>
				
			
		</div>
		<!-- END 980 WRAP -->		
				
	</body>
	<!-- END HTML BODY -->
</html>
	
	
	
