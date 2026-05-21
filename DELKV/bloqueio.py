from sqlite3 import Date
import mysql.connector
from mysql.connector import Error
from datetime import date, timedelta
import re
import smtplib
import email.message



try:
    con = mysql.connector.connect(host='localhost', database='biblioteca',user='root', password='')

    consuta_sql="select e.emprestimo, u.id_usuario, l.nome_livro, u.tipo, u.nome, u.bloqueado, u.email from emprestimo e, usuario u, exemplar ex, livro l where e.id_cliente_fk=u.id_usuario and e.id_exemplar_fk=ex.id_exemplar and ex.id_livro_fk=l.id_livro and e.devolucao IS NULL "
    cursor=con.cursor()
    cursor.execute(consuta_sql)
    linha=cursor.fetchall()


     
    for linha in linha:

        emprestimo=str(linha[0])
        emprestimo=int(re.sub('-', '', emprestimo))

        tipo=linha[3]

        if tipo==1:
            contador=7
        else:
            contador=15

        data_atual= date.today()
        data= str(data_atual-timedelta(contador))
        data=int(re.sub('-', '', data)) 


        if emprestimo <= data :

            #variaveis

            id=str(linha[1])
            livro=linha[2]
            nome=linha[4]
            bloqueio=linha[5]
            dia=linha[0]

            if emprestimo == data:
                devolucao = "hoje"
            else: 
                dat= dia+timedelta(contador)

                devolucao="no dia "+ dat.strftime("%d/%m/%Y")


            dia=dia.strftime("%d/%m/%Y")

            #email
            msg = email.message.Message()

            if bloqueio == 2:
                msg['Subject'] = "Atraso do livro"

                corpo_email = f"""
                <p>Caro usuario, {nome}</p><br>
                <p>O livro {livro} que você pegou no dia {dia}, venceu o prazo {devolucao} , por favor vá até a biblioteca e devolva imediatamente.</P>
                """
            else:
                msg['Subject'] = "Bloqueio do usuario"

                corpo_email = f"""
                <p>Caro usuario, {nome}</p><br>
                <p>O seu acesso ao sitema está sendo bloqueado, devido o vencimento do prazo de devolução do livro {livro},que você pegou no dia {dia}, vencido {devolucao}, para estar desbloqueando e acesando novamente o sitema basta devolver o livro na biblioteca  </p>
                """

                #bloquear usuario e enviar email
                inserir="update usuario set bloqueado='2' where id_usuario="+id
                cursor = con.cursor()
                cursor.execute(inserir)
                con.commit()
        
            #atualizaçõa do usso do gmail fez código parar de enviar o email, dia 30 de maio
            ''' 
            msg['Subject'] = "Recuperar senha"
            msg['From'] = 'bibliotecadelkv@gmail.com'
            msg['To'] = linha[6]
            password = 'batatadelkv' 
            msg.add_header('Content-Type', 'text/html')
            msg.set_payload(corpo_email )
            
            s = smtplib.SMTP('smtp.gmail.com: 587')
            s.starttls()
            # Login Credentials for sending the mail
            s.login(msg['From'], password)
            s.sendmail(msg['From'], [msg['To']], msg.as_string().encode('utf-8'))
            '''

except Error as e:
    print("Erros ao acessar a tabela",e)

finally:
    if (con.is_connected()):
        con.close()
        cursor.close()



