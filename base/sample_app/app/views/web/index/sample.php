<?php echo '<pre>';
//data below received by the "register" action. The names of the variables are the indexes of the return array
echo '<br/><br/>URL Params: e.g. url.com/module/controller/param1/param2/param3<br/>';
print_r($params);

echo '<br/><br/>Sample Arrays<br/>';
print_r($data);

echo '<br/><br/>Database Driven Config<br/>';
print_r($config);

echo '<br/><br/>Sample Controller Function Output<br/>';
print_r($controller_func);

echo '<br/><br/>Sample Model Function Output<br/>';
print_r($model_func);

echo '<br/><br/>Sample Database Query<br/>';
print_r($users_arr);

echo '<br/><br/>Response from Validation<br/>';
print_r($validator);