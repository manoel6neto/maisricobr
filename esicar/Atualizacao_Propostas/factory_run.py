# -*- coding: utf-8 -*-

##IMPORTS
from subprocess import Popen
import threading
import sys, time

##GLOBALS
NUM_SIMU_THREADS = 4
GLOBAL_ID = int(sys.argv[1])
INCREMENTO = int(sys.argv[2])

##Thread Class
class myThread (threading.Thread):

	def __init__(self, arg1, arg2):
		threading.Thread.__init__(self)
		self.arg1 = arg1
		self.arg2 = arg2

	def run(self):
		print "Chamada request esicar id_inicial: " + str(self.arg1) + " - incremento: " + str(self.arg2) + "\n"
		Popen("python /requests_esicar/request_gen.py " + str(self.arg1) + " " + str(self.arg2), shell = True).wait()
		print "Chamada finalizada request esicar id_inicial: " + str(self.arg1) + " - id_final: " + str(self.arg2) + "\n"

##MAIN
try:
	print "Iniciando thread. Id Inicial: " + str(GLOBAL_ID) + "\n"
	while(True):
		if threading.activeCount() <= NUM_SIMU_THREADS:
			try:
				myThread(GLOBAL_ID, INCREMENTO).start()
				GLOBAL_ID = GLOBAL_ID + INCREMENTO + 1
			except:
				print "Falha ao instanciar thread tentando novamente \n"
				time.sleep(30)
				continue
		else:
			print "Aguardando vaga na thread \n"
			time.sleep(30)
except Exception, e:
	print str(e) + "\n"
	sys.exit(1)
