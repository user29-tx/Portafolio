#!/bin/bash

for port in $(seq 1 65535);do
	timeout 1 bash -c "echo '' > /dev/tcp/172.17.0.3/$port" &>/dev/null && echo "$port - Active" &

done;wait
