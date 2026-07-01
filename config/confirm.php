<?php

echo"
	<script language='JavaScript' type='text/javascript'>
	function addSmiley(textToAdd){
		document.shout.pesan.value += textToAdd;
		document.shout.pesan.focus();
	}

	function addtext(text){
		document.shout.dest.value += text;
		document.hout.dest.focus();
	}

	function konfirmasi_delete() {

		var pilihan=confirm('Yakin Nih ???');

		if(pilihan){
			return true
		}
		else {
			return false
		}
	}


	</script>

";

?>