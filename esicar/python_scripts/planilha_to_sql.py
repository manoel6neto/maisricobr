# -*- coding: utf-8 -*-

#############
## IMPORTS ##
#############

import glob
import os
import subprocess
import sys
import time

import xlrd
from xlwt import easyxf #@UnresolvedImport @UnusedImport

import MySQLdb

selected_file = " Nenhum arquivo selecionado"
inseridos = []

ROOT='C:\\planilha\\'
#ROOT = '/planilha/' # For teste in mac
if not os.path.exists(ROOT):
    os.makedirs(ROOT)

# Connect to an existing database and open a cursor to perform database operations
dbmy = MySQLdb.connect(host=u'127.0.0.1', port=3306, user=u'root', passwd=u'', db=u'physi971_wp')
cur = dbmy.cursor()

tabela = u'cidade_tag'
colunas_db = [u'numero', u'cod_ibge', u'cidade', u'estado', u'gentilico', u'mesoregiao', u'microregiao', u'area', u'densidade', u'populacao', u'idhm', u'pib', u'renda']
colunas_planilha = [u'N.Número', u'Cod. IBGE', u'Cidade', u'Estado', u'Gentílico', u'Mesorregião', u'Microrregião', u'Área da unidade territorial (km²)', u'Densidade demográfica (hab/km²)', u'População Estimada 2013', u'IDHM', u'PIB', u'Renda Per Capta']

#Obtem os arquivos
def get_files():
    ###############################################
    ## Get NEWEST FILE WITHIN DECLARED DIRECTORY ##
    ###############################################    
   
    #Lista *.xls* dentro da pasta declarada na constante ROOT
    date_file_list = []
    for folder in glob.glob(ROOT):
        # select the type of file, for instance *.xls or all files *.*
        for filel in glob.glob(folder + '/*.xls*'):
            stats = os.stat(filel)
            lastmod_date = time.localtime(stats[8])
            date_file_tuple = lastmod_date, filel
            date_file_list.append(date_file_tuple)
    date_file_list.reverse()  # newest mod date now first
    date_file_list.sort()
    files=date_file_list
   
    return files

#Limpa o banco de dados caso necessário
def clean_db():
    query = "DELETE FROM " + tabela
    cur.execute(query)
    dbmy.commit()

#Adicionar no banco de dados
def fill_db(selected_file):
     
    #####################################
    ## OPENING SPREADSHEET (read-only) ##
    #####################################
    wb = xlrd.open_workbook(selected_file)
    sh_intel = wb.sheet_by_index(0)
    
    for rownum in range(sh_intel.nrows):
       
        #IF a row is empty escape it!
        if rownum != 0:
            
            #Pegando cada coluna da planilha
            numero = sh_intel.row_values(rownum)[0]
            cod_ibge = sh_intel.row_values(rownum)[1]
            cidade = sh_intel.row_values(rownum)[2]
            estado = sh_intel.row_values(rownum)[3]
            gentilico = sh_intel.row_values(rownum)[4]
            mesoregiao = sh_intel.row_values(rownum)[5]
            microregiao = sh_intel.row_values(rownum)[6]
            area = sh_intel.row_values(rownum)[7]
            densidade = sh_intel.row_values(rownum)[8]
            populacao = sh_intel.row_values(rownum)[9]
            idhm = sh_intel.row_values(rownum)[10]
            pib = sh_intel.row_values(rownum)[11]
            renda = sh_intel.row_values(rownum)[12]
            
            #Salvando cidades inseridas
            inseridos.append(cidade)
                                   
            #####################
            ## INSERINDO DADOS ##
            #####################
                                                      
            query = "INSERT INTO " + tabela + " (" + colunas_db[0] + ", " + colunas_db[1] + ", " + colunas_db[2] + ", " + colunas_db[3] + ", " + colunas_db[4] + ", " \
            + colunas_db[5] + ", " + colunas_db[6] + ", " + colunas_db[7] + ", " + colunas_db[8] + ", " + colunas_db[9] + ", " + colunas_db[10] + ", " + colunas_db[11] + ", " \
            + colunas_db[12] + ") VALUES (%d, %d, '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')" \
            % (int(numero), int(cod_ibge), cidade.replace("'", "").strip(), estado.replace("'", "").strip(), gentilico.replace("'", "").strip(), mesoregiao.replace("'", "").strip(), microregiao.replace("'", "").strip(), area, densidade, populacao, idhm, pib, renda)
            
            #Executando a query
            cur.execute(query)
            dbmy.commit()            
           
            print 60*'-'
            print "'%s', '%s'" % (estado.replace("'", "").strip(), cidade.replace("'", "").strip())
            print 60*'-'                
            

#Menu para selecionar arquivos e executar o inserte além de verificar os componentes inseridos
def start_menu(selected_file):
   
    # Limpa a tela
    cls = subprocess.call("cls", shell=True)
    #subprocess.call('clear', shell = True) #for mac
   
    # Coleta arquivos que estao na pasta
    files = get_files()
                       
    def head():
        print "\n"
        print " #=======================================================================#"
        print " #                PHISYS - CIDADE_TAG INSERT                             #"
        print " #=======================================================================#"
        print " #                                                                       #"
        print " #       PLANILHA: " + selected_file
        print " #                                                                       #"
       
    def init_options():
        print " #       1) Selecione planilha (cidades) a ser processada                #"
        print " #                                                                       #"
        print " #       2) Processar Planilha (cidades)                                 #"
        print " #                                                                       #"     
        print " #       3) Verificar LOG de execucao (cidades)                          #"
        print " #                                                                       #"
        print " #       4) SAIR                                                         #"
       
    def foot():
        print " #                                                                       #"
        print " #=======================================================================#"
        print "\n"
   
    #Start Menu
    head()
    init_options()
    foot()
    option = True	

    while option:
                   
        opt = raw_input(' Entre a opcao: ')
       
        ##################################
        ## OPÇÃO 1: seleciona planilha  ##
        ##################################
       
        if opt == '1':
           
            #Limpa a tela e calcula valor do botao voltar
            cls = subprocess.call("cls", shell=True)
            #subprocess.call('clear', shell = True) # for test in mac
            voltar_opt = len(files)+1
       
            head()
           
            #Printa planilhas disponiveis
            count = 0
            while count < len(files):
                opt_planilha = count+1
                filel = files[count][1]
                print " #       " + str(opt_planilha) + ") "+ filel.replace("C:\\planilha\\","")
                #print " #       " + str(opt_planilha) + ") "+ filel.replace("/ACC_Configurador/","") # for mac
                print " #                                                                                   #"
                count+=1
            print     " #       "+str(voltar_opt)+") Voltar"
           
            foot()
           
            #Seleciona planilha
            option_1 = True
            opt = raw_input(' Entre com uma opcao: ')
            while option_1:
                if int(opt) == voltar_opt:
                    start_menu(selected_file)               
                else:
                    opt = int(opt)-1
                    selected_file = files[opt][1]
                    start_menu(selected_file)
                    option_1 = False
           
        ################################      
        ## OPÇÃO 2: PROCESSA PLANILHA ##
        ################################
        elif opt == '2':
            print 'Iniciando processamento... Aguarde...'

            fill_db(selected_file)
            cur.close()
            dbmy.close()             
            start_menu(selected_file)
        
        ########################################
        ## OPÇÃO 3: Verifica LOG de resultado ##
        ########################################
        elif opt == '3':
            print "CIDADES INSERIDAS: \n" + str(inseridos)            
            raw_input("\n\nPressione Enter para continuar...")
            start_menu(selected_file)
              
        #QUIT
        elif opt == '4':                       
            sys.exit("\nFIM!!!")
        elif opt != '1' and opt != '2' and opt != '3' and opt != '4':
            print "\nOPCAO INVALIDA!!!\n"
        else:
            option = False           

##########
## MAIN ##
##########

if __name__ == "__main__":

    start_menu(selected_file)
    