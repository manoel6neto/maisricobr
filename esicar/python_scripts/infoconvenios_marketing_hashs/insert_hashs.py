# -*- coding: utf-8 -*-

import MySQLdb
import time

FILE_PATH = "hashs.txt"

## Conectando no banco de dados ##
db = MySQLdb.connect(host="192.168.0.102", user="root", passwd="Physis_2013", db="physis_esicar")
cur = db.cursor()

print "Abrindo o arquivo com as hashs"
time.sleep(5)

arquivo = open(FILE_PATH, "r")
lines = arquivo.readlines()
arquivo.close()

if len(lines) > 0:
	for line in lines:
		print "Inserindo a hash: " + str(line)
		cur.execute("INSERT INTO infoconvenio_marketing_hash (hash) VALUES ('%s')" % (str(line).strip()))
		db.commit()		

time.sleep(5)
db.commit()
time.sleep(5)
cur.close()
time.sleep(5)
db.commit()
time.sleep(5)
db.close()
print "Finalizado"
		