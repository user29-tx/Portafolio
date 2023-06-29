import os

output=b"./output"

encr = open(output, 'rb').read()
decr = open(output, 'wb')

for b in encr:
	decr.write(bytes([b ^ 0x9b]))
