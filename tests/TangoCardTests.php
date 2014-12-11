<?php
class TangoCardTests extends PHPUnit_Framework_TestCase{
	var $tango=null;
	var $accountid=null;
	var $accountname=null;

	public function setup(){
		$this->tango=new TangoCard("TangoTest","5xItr3dMDlEWAa9S4s7vYh7kQ01d5SFePPUoZZiK/vMfbo3A5BvJLAmD4tI=");
		$this->tango->setAppMode("sandbox");
		
		//Generates a random account name and Id and stores them
		$timestamp=time();
		$this->accountid="Id".$timestamp;
		$this->accountname="Name".$timestamp;

	}
	
	public function testAccountCreation(){
		
		$response=$this->tango->createAccount($this->accountname,$this->accountid,'aaa@aaada.com');
		
		$this->assertTrue($response->success);

		$info=$this->tango->getAccountInfo($this->accountname, $this->accountid);
		$this->assertTrue($info->success);

		$this->assertEquals($info->account->identifier,$this->accountid);
		$this->assertEquals($info->account->customer,$this->accountname);


	}
}
?>