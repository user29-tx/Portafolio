<?php

class Encryption
{

	protected $encryptMethod = 'AES-256-CBC';
	/**
     * Decrypt string.
     */
	public function decrypt($encryptedString, $key)
	{
		$json = json_decode(base64_decode($encryptedString), true);

		try {
			$salt = hex2bin($json["salt"]);
			$iv = hex2bin($json["iv"]);
		} catch (Exception $e) {
			return null;
		}

		$cipherText = base64_decode($json['ciphertext']);

		$iterations = intval(abs($json['iterations']));
		if ($iterations <= 0) {
			$iterations = 999;
		}
		$hashKey = hash_pbkdf2('sha512', $key, $salt, $iterations, ($this->encryptMethodLength() / 4));
		unset($iterations, $json, $salt);

		$decrypted= openssl_decrypt($cipherText , $this->encryptMethod, hex2bin($hashKey), OPENSSL_RAW_DATA, $iv);
		unset($cipherText, $hashKey, $iv);

		return $decrypted;
	}// decrypt


	/**
     * Encrypt string.
     */
	public function encrypt($string, $key)
	{
		$ivLength = openssl_cipher_iv_length($this->encryptMethod);
		$iv = openssl_random_pseudo_bytes($ivLength);

		$salt = openssl_random_pseudo_bytes(256);
		$iterations = 999;
		$hashKey = hash_pbkdf2('sha512', $key, $salt, $iterations, ($this->encryptMethodLength() / 4));

		$encryptedString = openssl_encrypt($string, $this->encryptMethod, hex2bin($hashKey), OPENSSL_RAW_DATA, $iv);

		$encryptedString = base64_encode($encryptedString);
		unset($hashKey);

		$output = ['ciphertext' => $encryptedString, 'iv' => bin2hex($iv), 'salt' => bin2hex($salt), 'iterations' => $iterations];
		unset($encryptedString, $iterations, $iv, $ivLength, $salt);

		return base64_encode(json_encode($output));
	}// encrypt


	/**
     * Get encrypt method length number (128, 192, 256).
     * 
     * @return integer.
     */
	protected function encryptMethodLength()
	{
		$number = filter_var($this->encryptMethod, FILTER_SANITIZE_NUMBER_INT);

		return intval(abs($number));
	}// encryptMethodLength


	/**
     * Set encryption method.
     */
	public function setCipherMethod($cipherMethod)
	{
		$this->encryptMethod = $cipherMethod;
	}// setCipherMethod
	
	function disPlayDecypted(){
    $cipher = 'eyJjaXBoZXJ0ZXh0IjoiVHI3cFFqRnlHemRoc1QyQjhLSFRtODZFYXhUb3pUY05iOWdwVjdKYmxIWFNDcVdOU2tmUmpCVmtPMGV0eUFTSDdVeG1MWERjNnhKY1I3aEVlMnRTMDhIMS90cGVQQjFLQ1JQdnViQTV3b3QvbFBDVzFFSHlVZFExZUJuMDVDM29qdmM3VkFMTXd2UkxaSGNOQ1JXQm9tdy9LWDhQS1I4SkNIWmRLMFhua3U2Z29SVkliczQycFRoc2w2MmdUaC95S3RIbGNUeHd6bWFuZWNrdnhPbzZXTS9SZG41RkdaR1ZoUXNxY3RDWWhvelJtaFA1ai9LeVFQbDNGcUNWSXNxbFVpWGRLM2xnYTBNdVVBZXhnQTFxSk8xbHZWZjYyQUloSnRhak4vQVJNOThiWXJMMXZIejVOSXlmMkI0K1M1R1RLZm5sTXROWjZiZEEwWFdUcGlvN250aHRRNUVDbUprcllPV1Y2Rkhwa2E1MkI0eEFGVURzbGkrclYvdkNHVzN2VnhuZFV6eGlPWXVXaFVWUXlHSHQwZXVubGIvaVZUbXBTS2lNOU9MZzlVWDNHekY5NGUzYTNNZ3VZTjhxTnZZMytLcjZKeDNlYk03SktmMXg1bm5QbGZSc01hRm1NcEwyaG8wSnRTTENhbUtweVlQMHBZMUFIWGU0eUx3VS9yVHhGdkkyUGFnL3JLVUcwSFhySnAxWEZXRStGb0hXMXBGZDR5dTdzaERVc2VLWVF3cWtHaCtxbFhnR1cwaFZwcXRDS3lCdnRMWUJCMFFZSS96dkFtdlU1Ni9TWTVxR0wvTXRMekZWbDJ2NXpaSFd1WEVjcFdtMXNkVDM3MXEvTHdXNFg3TWV1N2VFMTYrZERKRjFZVllqNGNFdVJ3RDhWU0RiMW8rUW1vZExpRXA5YjliOGljeGhMaWE0NE11UHdRVktJM1ViTmR6VGhlUjVzMjVxaS96MHdldFNBK283Z05iUjR3VVJVd0lOK3dMZkFkRGdzZXJEUkEvekxWRU1jWjRDTDQvcW5pdWNtbzBjdEw2dVphaWVsQy9nQmNBVlVxbGo1VjIzZkxiWXIyd0srN090N2ZGeDdQUnN1bVA5bXdKT1FOcmh5ZmVTSEZWNEZXS096U2J2YWtLMUtyeXJVdUI3OU1EbUl1ZmV0aGhvSGd4RGh4VG1SWUNDZ3g5MlRuZUF1eGNFYjJpeUtHb2RYN1J6ZEsyWmpqcCtYTVNoNmFEVWhEQ2hsSlh6c0FPQ090WXNWTjVkSGFWc3A5UWg5dnhrZFNka29hc2VZN1E5RnBlMy93NjhVcDYvN244MlpDb29TNjN5TERCOEdoWlhtUVBjTVBEeXd1M0x0aXVlN09JY2U0N2QxekUrVmk4amlIRjBVMmwvdm8rM2ZDTUIxU3lzQUVXc1pXUk1sOWVzcmxhdThNenlUWGkyZjgxa3RyTFFkSGhQY092RmFZbW5kS0djc1hXSnNOL256eXVvTlU5M3pERlZobFV6TVhGbWozWEhNTEpIeU96WXF1QjBiLzNXMk9paEM1cEZ5UjBvT2JEWUlxUFY4eWFzOEY3V1Exb2dJcEVhTk5BcGNUM3E0M2YyTFVWY3ByKythZEU3aGJrYmVOMVZGMDFqdGF5S0k4R0l6STBvYWhkSE9GUTNZZlcyWU5vY2NQV1dJQkw3WUkyTUF3R0E3OURheTMzeHVzQmJQNW8vNHJoclhReldGWnFKaTlBTWcydVZ3bzN2d3VVRkNIWkNtNGxKdXNiaFkyK3k1SklDbU1DNm04U1EvTUswR1V5WVlTSk12bDBVUUFTcjRWYmNpaTM3ZGpaVzFrKzVzRDNhanpxQVBncW9RcnRWNVp4ODh5Nms3ZDd4K2ZEVXh5VXBTNUt3OW5Ydm9tY1ZUMWNTRHd4aEc2QmlzS1dDY1Y1RXZqL2t4ZHZqZ3p4QjNtSlFFcVQya3ZNOGpqazVTSndCajV5dFdIQlZsYXhmMUNobjFUcTNtbnRlTjVHcmxxYXQ0Z3c5ajMzcFR3cmlZT3Z1RHAxQ2J3N1NocURoYWswTWt1eGRxaGRXRE5LYzFHL3M1TEdpR3dCdlBxT1M4TTAyOHBBSWQ4YzNtK0VyMXFUSEdLZXZJdnpseHZGTjYzZy83QklYaFRocis0cXlrVWFRMTVEU2VVd2lNUkhUVlhpcVlwc3JkMzVDUFRVOUl2cVN0aGZsbFhnTnl5R0djSFpvUkhLaE14SW41aGZNU1QzdGMza0JpWUh1R3haOEp2QzFCYUtrSHpiVVV6Tjg2YVczN2hSWVdhTnFnZ2JDMXVrTU5CZFZHbXhGaVRPd3pqRkh0ZFdxZ2RETnpPUVlwM3ZETmhSOXFDZUlpMVM3YmxFM1lYZkw5T3A0QlI3bW9mRTFNWWluYS9hUElhNTBvS3cxcHU3YjNqNGNRci9CYm1PeURKM2I5YlhwT0xpV0N3RjlEcHlMbDhkWWZvcm1uVThDaFIyWVU5MWtBcXhkZnJvRUpRM1Nzdm92TU40TGhraktBYW42YTRuWFoxQW8zK0NldytERGhYUVhFQ24zbGNYTklyQkJmS2ZkZGFBWTE0WEM5cDBTZEttRFJBdjBodDRPTk0rT1pIbkdMdGlRZFRpc0c4VVB2ODRFSDFoczFYNXh3YXNCb0hBSGRMQnRJSnVIRDhjYU1ORlkxQmROaXk3cURLU2ZtTWU4MTArVXB5UkZJMUVLRndlZGN2YUZqSTNMRzhIajUyZTVmTVltMGU3ckNrRXN0SGlLRTU2SDlkaU8xRVRNa2JOTFA3N0svNDd3eW0rZ3lQS0JnL2pVODMyelEwSGJyNGllVk1wY3d4b0tpRkFDZlRiVzc5dVJybEtibDJldE42NFU2NkhtU3NteFV2TDF5OHlzbXVNMW5CTW5FWUVRdmg1eXBoOGdpTmovZnRENlVlYms0K1pWZW54cncremlPNHVrWnZFeWlab25Ec1VydExSUEcrRU5mSXVIMG1tajNibFdUeSszQzV4QVZSSHhhR0dIUm1xUTBSVDVONUZaZEEyVjVZRno1aDAvdDBoTmJLUldxMlljWjNPL0hDemQ0cmRyalFSY1g4WGFzdXZRU2NwSW5rei9LVFQ5VkJTZUppdm55OElVdUNnMWRsVUFiYTM3QTVWa0hjK3BEeVJzQXdxZWlSTHJoTTRrdkdHUTZyc3JmbzdpcEFkVVRtQytqaFlqQkRGcWVjMXl4UW5QN3FQUU5rcGdUekhSS2wrN0hCbWc0SU5qeDNQYzJRK25SZ2ZSeXV1eWxyYkFEK1QzcUcyU2FwbDFZTkdjdElyRFVLbW5keWlXTEdJVE1aV0lrcDZyZ2VVQ0lvckQ1c1JiRXgxZ0lVUzkzNEp5L1NDQVZ2c284c1hKeFl6WmRwYnBIK0ZqdDRZYlFCaVpGdWZCZTFkU29KdzFFQmhKUWpYUjZmWjNxcGdXcCs4dzZFVWprbzM0aEJXOFpmaFhTMitzTk9aZERXelQ4d0JSMTJWRTQ0OFdLVzBNM0xyN01kWXl3WkY3Q3I2alR0UXZFNW5CdTlOUHFDZ1dmZTRRcE5vSW45d1VWSVV3citEbStIK3U1cWxKZk1RUGIxRkFSNXFST2owQmQ3RjRoK1VvUUtUbE5FcUN6bFdXMGJZM1l6dWRUa1k4VTN3cTRmbjlEQUlwWGtvZVRNNERpMndhUjBZYXZVaUJMcHBuMXp0a3JIaElYSityWmRqYXdEZ01reVdzb0ZYakZhR2hZTjRvdlBHaXU4NVU5VmU2bndmUjVSVGxDUVhaMXpLVzlrNk9JMHNGTHRrU1F0QXRrUFpSVWl1QXM4SDNkNTQ3VkZlTVpJNFRJcVJyYk9CeDB1R2c4eXRtYzZ3NFpZNFlJSVprd0NLVXBRejJQZmdibmNJOXhSMHBwMDRKVkQ4R2Vuczg4M3JGUjJFQnhTUFZxSjdVSmxESWp4ZDRIU21rK2ZPS3dnS2FYNFFDTGNiVWxLalFKSWlXZFFLQ1pOUW5aeGJNWW5LM0NHU2FucmdWNERhc0pPSThNaVZ1VDUxd3I4VFlEL2VaTFErOCt6Q3pmMnF1VGNWNEtnSlByaUFJQWJNL0ovZWxZSTJpcmVYbXppbVp0N2MydWdpYzhZVVM3eGtocTRYbUlPVGx3RDEzZWk4ZkhVNStYdVBzS1IyUWxVM1Ayd1pFb0UrRnBqVExxa2k4NlBBcU82QjZiWTVtNmJHTERpZ0lVUWtNVExqOWZ4dFEwVDgxNC9oSk4vS2tGSFRFWEduWnVwQkpWT0s3Q2hxazZJVmVCZVh3eU1tUkw3TmhNajBYOG1JR2g2YVJmVFpZQVZUczBkWnVJSTVOUURqTGRMb3Jkb3ZQL0lxdjhWam0wOG9FaEhtZUszYzI4eVBJb3VQTlRsL1lMZ1M3NlBnbU84dGtZZTR1Z3JKK2dKMzhHc2NzQm91REl3bk82OG1ZV1o5c1F5cG85U0tvbE51TjJ5VCtFQWE1eEcwOUc3aXZaS0hZVWQybU0xQlcxaHkveVBJcUZEcXR4ZTgyMWp4Vm85UVltS2krOUUvanUraXdkYVNIVDJFSWZzMk1CQUFlVzVCcEIvS0MvbGJGRzljWmpnMERURW1zUzIrczYwR3pNdz09IiwiaXYiOiJiNWE1ZWJhMTQ1MzVkYTZiYTIwZDNkNGI5ZjdiZGFjYyIsInNhbHQiOiJkMDgwNzAzM2JkNDNlYWZkNmVlNDdkMzQ5ZDQzODA0MzY1OWMyNTBhOWQ3OTc3NGJiMmEwYzU1OWFhNjA4ZDNiNjFkNDVkZGViNTU0NTc4ZTNlYTQwODJmNzBmYmVjYWM1ZWM2NWE4NjlmMzI0MjU4NjUxMGZkMmYyZTBkYTkzZmM1MDEzOTk2OTQ5MWNkNDU2MzA2MDExZjY5NWFkYzJkYjBlMTMwNDM2YWViZjJmMzRmMDBkZmFkZDJjYWZiZWZjYzg0MmUzNzk4MmI5NmNmNDhkZDc4YjczYjYxNGY0YzljOTIwYzllY2NlOWRjY2Y2NmM3YmNiYzQ5YWFiM2NmYzI5N2UxMGY2NDcwY2I2YjY2NzAxYTFhNjhlZDBiYWQ5OWZiMDk0NDQyZmUzYjEwNDEyN2ZjMTk2Y2FhYmZhY2I3YjU5MTc4NjE1OTZiZjU0NWM3NDkwNjZiNGJlZmNiYTg2Zjg1NmM5M2U2YTI5MzZjNjM2NGQ3NGEyMjQ2Nzc5ZTJmYjEyYmMwYzUwYjE5NmFkOTI0Nzc0NDU4YjUyZjQxYmU5NTUyM2ExMDljMjMzZWM2NzFiNmIyNWFhYjJlNWU1ZjVkNDJhMTVlYmNiMDNmZjE5ODFiYmJiODdkMGFkM2M1NDRlMzUyN2QwOWU3YmI5YjA5ZjI1NjYzYzY3YSIsIml0ZXJhdGlvbnMiOjk5OX0=';
    $key1 = '(@McEXk%HU#{/R3s';
    $rs=$this->decrypt($cipher,$key1);
    echo $rs;
    }
}
    
$result= new Encryption();
echo $result->disPlayDecypted();

?>
