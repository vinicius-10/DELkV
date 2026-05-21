
import smtplib
import email.message


#cod = sys.argv[1]
cod = 1
cod = str(cod)
email_usuario = "valucard10@gmail.com"
#email_usauario  = sys.argv[2]

 
corpo_email = f"""
<p>Recuperação da senha</p><br>
<p>Para estar recuperando a  sua senha acesse o <a href=http://localhost/Biblioteca/RecuSenha.php?cod={cod}> link</a></p>
<p>Se o link não estiver funcionando copie e cole o link abaixo:<br>
http://localhost/Biblioteca/RecuSenha.php?cod=cod{cod}</p>
    """
#atualizaçõa do usso do gmail fez código parar de enviar o email, dia 30 de maio
"""
msg = email.message.Message()
msg['Subject'] = "Recuperar senha"
msg['From'] = 'bibliotecadelkv@gmail.com'
msg['To'] = email_usuario 
password = 'batatadelkv' 
msg.add_header('Content-Type', 'text/html')
msg.set_payload(corpo_email )

s = smtplib.SMTP('smtp.gmail.com: 587')
s.starttls()
# Login Credentials for sending the mail
s.login(email_usuario , password)
s.sendmail(msg['From'], [msg['To']], msg.as_string().encode('utf-8'))
print('Email enviado')

"""