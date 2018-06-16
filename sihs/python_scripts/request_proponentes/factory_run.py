# -*- coding: utf-8 -*-

##IMPORTS
from subprocess import Popen
import threading
import sys, time

##GLOBALS
NUM_SIMU_THREADS = 20
GLOBAL_ID = 1

##Thread Class
class myThread (threading.Thread):

	def __init__(self, arg1, arg2):
		threading.Thread.__init__(self)
		self.arg1 = arg1
		self.arg2 = arg2

	def run(self):
		print "Chamada request sihs id_inicial: " + str(self.arg1) + " - id_final: " + str(self.arg2)
		Popen("python /request_proponentes/request_gen.py " + str(self.arg1) + " " + str(self.arg2), shell = True).wait()
		print "Chamada finalizada request sihs id_inicial: " + str(self.arg1) + " - id_final: " + str(self.arg2)

##MAIN
try:	
	print "Iniciando processamento das consultas !! ID_SICONV inicial: " + str(GLOBAL_ID)
	while(True):
		if threading.activeCount() <= NUM_SIMU_THREADS:
			try:
				myThread(GLOBAL_ID, (GLOBAL_ID + 5)).start()
				GLOBAL_ID = GLOBAL_ID + 6
			except:
				print "Falha ao instanciar thread tentando novamente"
				time.sleep(5)
				continue
		else:
			time.sleep(30)
except Exception, e:
	print str(e)
	sys.exit(1)




	


