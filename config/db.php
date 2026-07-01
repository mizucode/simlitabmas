<?php

/* ***************************************************************************************
 * System Configure
 * 
 * PHP Version 5
 * Database MySQL
 * 
 * LICENSE : This source file is subject to the MIT License, available
 * 
 * @author		Sofhian Fazrin Nasrulloh <www.fazrin.com> <fazrin.nashrullah@yahoo.com>
 * @copyright	GNU Public License
 * @phones		083-824-050-015 
 * 
 * This program is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software Foundation;
 * either version 2 of the License, or (at your option) any later version.
 * 
 * THIS SCRIPT IS PROVIDED AS IS, WITHOUT ANY WARRANTY OR GUARANTEE OF ANY KIND
 * ****************************************************************************************
 */

if (valid != '1') {
	header('location:/login');
} else {

	define('host', 'localhost');
	define('user', 'root');
	define('pass', '');
	define('dbase', 'siamik');

	$con = mysqli_connect(host, user, pass) OR DIE(" Koneksi Gagal ");

	mysqli_select_db($con, dbase) OR DIE("Cannot connect to database ");

}

?>