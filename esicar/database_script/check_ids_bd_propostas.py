# -*- coding: utf-8 -*-

#Imports
import MySQLdb

#Conectando no banco de dados
print "Conectando no banco de dados."
db = MySQLdb.connect(host="convenios.physisbrasil.com.br", user="root", passwd="Physis_2013", db="physis_esicar")
cur = db.cursor()
cur.execute("SELECT DISTINCT(id_siconv) FROM banco_proposta WHERE id_siconv is not null ORDER BY cast(id_siconv as unsigned)")
result = cur.fetchall()

#Pegando o valor das extremidades
firstId = int(result[0][0])
lastId = int(result[len(result) - 1][0])
print "Id inicial: %d" % (firstId)
print "Id final: %d" % (lastId)

#Montando a lista com os ids existentes
listResult = []
print "Gerando a lista com os ids existentes..."
for tupleResult in result:
    listResult.append(int(tupleResult[0]))
print "Finalizado processo de gerar a lista de ids existentes."
    
#Verificando os existentes e salvando os que precisam de verificação
print "Processando Ids que precisam de verificação..."
listIdsForInsert = []
for i in range(firstId, lastId):
    if not i in listResult:
        listIdsForInsert.append(i)
        print "Adicionado ID: %d" % (i)
        
#Insert no banco de dados dos ids que necessitam de verificação
print "Iniciando inserts no banco de dados."
for idInsert in listIdsForInsert:
    print "Insert ID: %d" % (idInsert)
    query = "INSERT INTO ids_propostas_faltando (id_proposta_siconv) VALUES (%s)" % (idInsert)
    cur.execute(query)
print "Finalizado insert!"

#Gravando os inserts no banco de dados
print "Gravando alterações no banco de dados."
db.commit()

#Finalizando o processo
print "Finalizando processo e conexão com o banco de dados."
cur.close()
db.close()

