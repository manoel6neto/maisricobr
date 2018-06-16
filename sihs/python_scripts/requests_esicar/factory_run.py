# -*- coding: utf-8 -*-

##IMPORTS
from subprocess import Popen
import MySQLdb
import threading
import sys, time

##GLOBALS
NUM_SIMU_THREADS = 1
GLOBAL_ID = 0

##Thread Class
class myThread (threading.Thread):

	def __init__(self, arg1, arg2):
		threading.Thread.__init__(self)
		self.arg1 = arg1
		self.arg2 = arg2

	def run(self):
		print "Chamada request sihs id_inicial: " + str(self.arg1) + " - id_final: " + str(self.arg2)
		Popen("python /requests_sihs/request_gen.py " + str(self.arg1) + " " + str(self.arg2), shell = True).wait()
		print "Chamada finalizada request sihs id_inicial: " + str(self.arg1) + " - id_final: " + str(self.arg2)

##MAIN
try:
	#Get from database the las valid insert id_siconv from banco_proposta
	print "Consultando ultimo id_siconv valido no banco de dados"
	db = MySQLdb.connect(host="secretaria.physisbrasil.com.br", user="root", passwd="Physis_2013", db="physis_sihs")
	cur = db.cursor()
	cur.execute("SELECT id_siconv FROM banco_proposta ORDER BY cast(id_siconv as unsigned) DESC LIMIT 1")
	GLOBAL_ID = int(cur.fetchall()[0][0])
	GLOBAL_ID = GLOBAL_ID

	print "Iniciando processamento das consultas !! ID_SICONV inicial: " + str(GLOBAL_ID)
	while(True):
		if threading.activeCount() <= NUM_SIMU_THREADS:
			try:
				myThread(GLOBAL_ID, (GLOBAL_ID + 1)).start()
				GLOBAL_ID = GLOBAL_ID + 2
			except:
				print "Falha ao instanciar thread tentando novamente"
				time.sleep(30)
				continue
		else:
			print "Aguardando vaga na thread"
			time.sleep(30)
except Exception, e:
	print str(e)
	sys.exit(1)




	


