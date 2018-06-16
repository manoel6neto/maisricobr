# -*- coding: utf-8 -*-

from subprocess import Popen, PIPE
import smtplib
import os
import sys
import random
import string
import MySQLdb


PATH_SCRIPTS = "/BACKUP/"
PATH_BACKUPS = PATH_SCRIPTS
SIZE_RANDOM = 10
FINAL_BACKUP_FILE_NAME = "backup_physis_esicar.sql"


def principal():
    status = Popen("/usr/bin/service apache2 stop", shell=True).wait()
    if status == 0:
        status = Popen("/usr/bin/service mysql restart", shell=True).wait()
        if status == 0:
            backup_file_name = gerarnomearquivo(SIZE_RANDOM)
            executabackup(backup_file_name + ".sql", FINAL_BACKUP_FILE_NAME)
            #restaurarhostgator(FINAL_BACKUP_FILE_NAME)
            Popen("/usr/bin/service apache2 start", shell=True).wait()
            Popen("/usr/bin/service mysql restart", shell=True).wait()
#	    Popen('sshpass -p "u66aH1iHo3" rsync -rav --progress -e "ssh -p 2222" /BACKUP/backup_physis_esicar.sql physi971@179.190.55.139:/home/physi971/backup_godaddy_esicar_database', shell=True).wait()


def executabackup(backup_file_name, final_file_name):
    os.chdir(PATH_BACKUPS)
    status = Popen("mysqldump -u root -pPhysis_2013 physis_esicar -f -r %s" % backup_file_name, stdout = PIPE, shell = True).wait()
    if status == 0:
	try:
	    os.remove(final_file_name)
	except:
	    pass
        os.rename(backup_file_name, final_file_name)

    #enviaremail(status, "goddady")


def gerarnomearquivo(length):
    return ''.join(random.choice(string.lowercase) for i in range(length))


def restaurarhostgator(final_file_name):
    print "iniciou hostgator"
    os.chdir(PATH_BACKUPS)

    dbmy = MySQLdb.connect(host=u'physisbrasil.com.br', port=3306, user=u'physi971_wp', passwd=u'Physis_123*')
    db1 = dbmy.cursor()
    db1.execute('DROP DATABASE physi971_thomas')
    dbmy.commit()
    db1.execute('CREATE DATABASE physi971_thomas')
    dbmy.commit()
    db1.close()
    dbmy.close()

    status = Popen("mysql -uphysi971_wp -pPhysis_123* --default-character-set=utf8 -h physisbrasil.com.br physi971_thomas < %s" % final_file_name, shell=True).wait()

    print "finalizou"

    #enviaremail(status, "hostgator")


def enviaremail(status, servidor):
    smtp_server = 'smtp.gmail.com'
    smtp_port = 587
    password = 'Physis_2015'

    sender = 'physisbrasil@gmail.com'
    recipient = 'manoel.carvalho.neto@live.com'
    subject = str(servidor) + ':  CODIGO DE RETORNO: ' + str(status)
    body = str(servidor) + ': CODIGO DE RETORNO: ' + str(status)

    body = "" + body + ""

    headers = ["From: " + sender,
               "Subject: " + subject,
               "To: " + recipient,
               "MIME-Version: 1.0",
               "Content-Type: text/html"]
    headers = "\r\n".join(headers)

    try:
        session = smtplib.SMTP(smtp_server, smtp_port)
        session.ehlo()
        session.starttls()
        session.ehlo
        session.login(sender, password)
        session.sendmail(sender, recipient, headers + "\r\n\r\n" + body)
        session.quit()

    except Exception, ex:
	print ex

if __name__ == "__main__":
    try:
        principal()
    except Exception, e:
        print "Falha durante a execucao: %s" % e
