<?php

interface Payment_Interface {
	public function pay($amount, $params,$return_url,$pay);
	public function callback();
}