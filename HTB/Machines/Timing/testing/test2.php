while (true){
echo date("D M j G:i:s T Y");
echo " = ";
echo md5('$file_hash' .time());
echo "\n";
sleep(1);
}
