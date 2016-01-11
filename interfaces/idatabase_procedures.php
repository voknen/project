<?php
if (!interface_exists("CommonInterface")) {
	interface CommonInterface
	{
		public function add($query);
		public function select($query);
		public function update($query);
		public function delete($query);
	}
}
