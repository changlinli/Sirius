<div class="pagination pull-right">
<ul>
<?php
    $adjacents = 3; 
    
    $page = $pagination['current_page'];
    $prev = $page - 1;                          
    $next = $page + 1;                           
    $lastpage = $pagination['num_pages']; 
    $lpm1 = $lastpage - 1;                       
         
    if ($lastpage > 1) {    
        if ($page > 1) {
            echo '<li><a href="/'.$this->registry->router->controller_name.'/'.$this->registry->router->action_name.'/'.$prev.'" page="'.$prev.'">Prev</a></li>';
        } else {
            echo '<li><a href="#" page="0">Prev</a></li>';
        }    

        if ($lastpage < 7 + ($adjacents * 2)) { 
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page) {
                    echo '<li class="active"><a href="#" title="" page="0">'.$counter.'</a></li>';
                } else {
                    echo '<li><a href="/'.$this->registry->router->controller_name.'/'.$this->registry->router->action_name.'/'.$counter.'" page="'.$counter.'">'.$counter.'</a></li>';
                }   
            }
        } else if ($lastpage > 5 + ($adjacents * 2)) {
            if ($page < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $page) {
                        echo '<li class="active"><a href="#" title="" page="0">'.$counter.'</a></li>';
                    } else {
                        echo '<li><a href="/'.$this->registry->router->controller_name.'/'.$this->registry->router->action_name.'/'.$counter.'" page="'.$counter.'">'.$counter.'</a></li>';
                    }                    
                }
    
                echo "<li>...</li>";
                echo '<li><a href="/'.$this->registry->router->controller_name.'/'.$this->registry->router->action_name.'/'.$lpm1.'" page="'.$lpm1.'">'.$lpm1.'</a></li>';
                echo '<li><a href="/'.$this->registry->router->controller_name.'/'.$this->registry->router->action_name.'/'.$lastpage.'" page="'.$lastpage.'">'.$lastpage.'</a></li>';
            } else if($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                echo '<li><a href="/'.$this->registry->router->controller_name.'/'.$this->registry->router->action_name.'/1" page="1">1</a></li>';
                echo '<li><a href="/'.$this->registry->router->controller_name.'/'.$this->registry->router->action_name.'/2" page="2">2</a></li>';
                echo "<li>...</li>";
        
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page) {
                        echo '<li class="active"><a href="#" title="" page="0">'.$counter.'</li>';
                    } else {
                        echo '<li><a href="/'.$this->registry->router->controller_name.'/'.$this->registry->router->action_name.'/'.$counter.'" page="'.$counter.'">'.$counter.'</a></li>';
                    }                                               
                }
        
                echo "<li>...</li>";
                echo '<li><a href="/'.$this->registry->router->controller_name.'/'.$this->registry->router->action_name.'/'.$lpm1.'" page="'.$lpm1.'">'.$lpm1.'</a></li>';
                echo '<li><a href="/'.$this->registry->router->controller_name.'/'.$this->registry->router->action_name.'/'.$lastpage.'" page="'.$lastpage.'">'.$lastpage.'</a></li>';        
            } else {
                echo '<li><a href="/'.$this->registry->router->controller_name.'/'.$this->registry->router->action_name.'/1" page="1">1</a></li>';
                echo '<li><a href="/'.$this->registry->router->controller_name.'/'.$this->registry->router->action_name.'/2" page="2">2</a></li>';
                echo "<li>...</li>";
                
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page) {
                        echo '<li class="active"><a href="#" page="0">'.$counter.'</li>';
                    } else {
                        echo '<li><a href="/'.$this->registry->router->controller_name.'/'.$this->registry->router->action_name.'/'.$counter.'" page="'.$counter.'">'.$counter.'</a></li>'; 
                    }                    
                }
            }
        } else if($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
            echo '<li><a href="/'.$this->registry->router->controller_name.'/'.$this->registry->router->action_name.'/1" page="1">1</a></li>';
            echo '<li><a href="/'.$this->registry->router->controller_name.'/'.$this->registry->router->action_name.'/2" page="2">2</a></li>';
            echo "<li>...</li>";
    
            for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                if ($counter == $page) {
                    echo '<li class="active"><a href="#" title="" page="0">'.$counter.'</li>';
                } else {
                    echo '<li><a href="/'.$this->registry->router->controller_name.'/'.$this->registry->router->action_name.'/'.$counter.'" page="'.$counter.'">'.$counter.'</a></li>';
                }                                               
            }
    
            echo "<li>...</li>";
            echo '<li><a href="#" page="'.$lpm1.'">'.$lpm1.'</a></li>';
            echo '<li><a href="#" page="'.$lastpage.'">'.$lastpage.'</a></li>';        
        } else {
            echo '<li><a href="/'.$this->registry->router->controller_name.'/'.$this->registry->router->action_name.'/1" page="1">1</a></li>';
            echo '<li><a href="/'.$this->registry->router->controller_name.'/'.$this->registry->router->action_name.'/2" page="2">2</a></li>';
            echo "<li>...</li>";
            
            for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                if ($counter == $page) {
                    echo '<li class="active"><a href="#" page="0">'.$counter.'</li>';
                } else {
                    echo '<li><a href="/'.$this->registry->router->controller_name.'/'.$this->registry->router->action_name.'/'.$counter.'" page="'.$counter.'">'.$counter.'</a></li>'; 
                }                    
            }
        }
        
        if ($page <= $lastpage - 1) {
            echo '<li><a href="/'.$this->registry->router->controller_name.'/'.$this->registry->router->action_name.'/'.$next.'" title="" page="'.$next.'">Next</a></li>';
        } else {
            echo '<li><a href="#" title="" page="0">Next</a></li>'; 
        }                
    }
?>            
</ul>
</div>