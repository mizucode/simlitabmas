<?php

if (valid!='1'){
	header('location:/login');
}

else {

	$globalvar['news']='read';
	$globalvar['page']='page';
	$globalvar['cat']='cat';

}

?>