# -*- coding: utf-8 -*-
#!/usr/bin/env python

from subprocess import Popen
import smtplib, time
import sys

## Destroi e cria as tabelas e as visoes ##

caminho = '/scripts/banco_de_dados/'
script = 'update_views.sql'
comando = 'mysql -uroot -pPhysis_2013 --default-character-set=utf8 physis_esicar < ' + caminho + ' ' + script

## Executando o comando sql ##
codigo_saida = Popen(comando, Shell = True).wait()
  
## Envia email informando da execucao ##
SMTP_SERVER = 'smtp.gmail.com'
SMTP_PORT = 465
password = 'mxthomasmx'

sender = 'manoel.carvalho.neto@gmail.com'
recipient = 'manoel.carvalho.neto@live.com'
subject = 'Executado script para update de visoes physis esicar!! CODIGO DE RETORNO: ' + codigo_saida
body = 'Script de atualizacao executado no servidor!! CODIGO DE RETORNO: ' + codigo_saida

body = "" + body + ""
 
headers = ["From: " + sender,
           "Subject: " + subject,
           "To: " + recipient,
           "MIME-Version: 1.0",
           "Content-Type: text/html"]
headers = "\r\n".join(headers)

try:
  session = smtplib.SMTP(SMTP_SERVER, SMTP_PORT)
  session.ehlo()
  session.starttls()
  session.ehlo
  session.login(sender, password)
  session.sendmail(sender, recipient, headers + "\r\n\r\n" + body)
  session.quit()
  
  print "Email enviado com sucesso!!" 
  sys.exit(0)
except Exception, e:
  print "Falha durante o envio de email: " + str(e)
  sys.exit(1)




 
