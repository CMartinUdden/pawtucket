<?php
	$va_errors = $this->getVar('errors');
	
	if (sizeof($va_errors)) {
			print json_encode(array('status' => 'error', 'errors' => $va_errors, 'set_id' => $this->getVar('set_id'), 'item_id' => $this->getVar('item_id')));
	} else {
			print json_encode(array('status' => 'ok', 'set_id' => $this->getVar('set_id'), 'item_id' => $this->getVar('item_id')));
	}
?>