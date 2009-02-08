<?php
$this->dispatcher->connect('routing.load_configuration', array('w3sRouting', 'listenToRoutingLoadConfigurationEvent'));